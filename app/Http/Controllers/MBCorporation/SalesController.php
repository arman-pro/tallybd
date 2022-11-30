<?php

namespace App\Http\Controllers\MbCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Godown;
use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\SaleMen;
use App\Item;
use App\SalesAddList;
use App\SalesOrderAddList;
use App\SalesReturnAddList;
use App\StockDetail;
use App\DemoProductAddOnVoucher;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Helpers\Product;
use App\ItemCount;
use App\LedgerSummary;
use App\SaleDetails;
use App\StockHistory;
use App\Traits\SMS;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;
use Session;
use DataTables;

class SalesController extends Controller
{

    use SMS;

    public function send_sales_sms($id)
    {

        try {
        $person =  SalesAddList::whereId($id)->with('ledger')->first();
        if(optional($person->ledger)->account_ledger_phone){
            $mobile = optional($person->ledger)->account_ledger_phone;
            $text =  'Hi , '.optional($person->ledger)->account_name.'. Sale Invoice No :'.$person->product_id_list.', Total Amount : '.$person->grand_total;
            SMS::sendSMS($mobile, $text);
        }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }
    public function send_sales_return_sms($id)
    {

        try {
        $person =  SalesReturnAddList::whereId($id)->with('ledger')->first();
        if(optional($person->ledger)->account_ledger_phone){
            $mobile = optional($person->ledger)->account_ledger_phone;
            $text =  'Hi , '.optional($person->ledger)->account_name.'. Sale Return Invoice No :'.$person->product_id_list.', Total Amount : '.$person->grand_total;
            SMS::sendSMS($mobile, $text);
        }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }

    public function sale_order_list_datatable()
    {
        $sale_order_lists = SalesOrderAddList::with(['ledgers', 'demoProducts'])->orderBy('date', 'desc');
        return Datatables()->eloquent($sale_order_lists)
        ->addIndexColumn()
        ->addColumn('ledger_name', function(SalesOrderAddList $sale_order_list) {
            return $sale_order_list->ledgers->account_name ?? "";
        })
        ->addColumn('item_details', function(SalesOrderAddList $sale_order_list) {
            return $sale_order_list->demoProducts->pluck('item.name')->implode('<br/>');
        })
        ->addColumn('qty', function(SalesOrderAddList $sale_order_list) {
            return $sale_order_list->demoProducts->pluck('qty')->implode('<br/>');
        })
        ->addColumn('total_price', function(SalesOrderAddList $sale_order_list) {
            return $sale_order_list->demoProducts->map(function($demo_product){
                return new_number_format(($demo_product->qty ?? 0) * ($demo_product->price));
            })->implode('<br/>');
        })
        ->addColumn('action', function(SalesOrderAddList $sale_order_list) {
            return make_action_btn([
                '<a href="'.route("view_sales_order", ['product_id_list' => $sale_order_list->id]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_sales_order",['product_id_list' => $sale_order_list->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="javascript:void(0)" data-id="'.$sale_order_list->product_id_list.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("sales_order_approved", ['product_id_list' => $sale_order_list->id, 'md_signature' => 1]).'" class="dropdown-item"><i class="fas fa-check"></i> Approve</a>',
            ]);
        })
        ->make(true);
    }   
    
    // order sales 
    
    public function sales_order_addlist(Request $request)
    {
        if($request->ajax()) {
            return $this->sale_order_list_datatable();
        }
        $PurchasesAddList = SalesOrderAddList::get();
        return view('MBCorporationHome.transaction.sales_order_addlist.index', compact('PurchasesAddList'));
    }

    public function sales_order_addlist_form()
    {
        $Godwn = Godown::get();   
        $Item = Item::get();
        $account = AccountLedger::get();
        return view('MBCorporationHome.transaction.sales_order_addlist.sales_order_addlist_form', compact('Godwn', 'Item', 'account'));
    }

    public function SaveAllData_sales_order_store(Request $request, Helper $helper)
    {
        //echo $request->delivered_to_details;
        //dd($request);
        $request->validate([
            'product_id_list' => 'required|unique:sales_order_add_lists',
            'account_ledger_id' => 'required',

            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            $this->addOnDemoProductStore($request);
            $for_name = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
            //dd($for_name);

            $total_subtotal = 0;
            $total_subtotal =   $for_name->sum('subtotal_on_product');

            $salesAddList = SalesOrderAddList::create([
                'date' => $request->date,
                'product_id_list' => $request->product_id_list,
                'account_ledger_id' => $request->account_ledger_id,
                'delivered_to_details' => $request->delivered_to_details,
                 
                'grand_total' => $total_subtotal,
                'shipping_details' => $request->shipping_details,
            ]);
            //dd($salesAddList);

        (new LogActivity)->addToLog('Sales Order Add Created .');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            //throw $th;
            return response()->json($ex->getMessage());
        }
        if($request->print){
            return redirect()->action('MBCorporation\SalesController@print_sales_invoice',[ $salesAddList->product_id_list] );
        }
        return redirect()->to('sales_order_addlist');
    }


    public function delete_sales_order($product_id_list)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        try {
            DB::beginTransaction();
 
            DemoProductAddOnVoucher::where('product_id_list', $product_id_list)->where('page_name',  "sales_order_addlist")->delete();
            SalesOrderAddList::where('product_id_list', $product_id_list)->delete();
            (new LogActivity)->addToLog('Sales Order Add Deleted .');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
 
    }

    public function edit_sales_order($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $salesAddList = SalesOrderAddList::whereId($id)->first();
        $items = Item::get();
        $expense_ledgers = AccountLedger::whereIn('account_group_id', [9,11])->searching('account_name', $request->name)->get(['id', 'account_name']);
        return view('MBCorporationHome.transaction.sales_order_addlist.edit_sales_order_addlist', compact('items', 'salesAddList', 'expense_ledgers'));
    }


    public function UpdateSalesOrderAddList(Request $request,Helper $helper, $id)
    {
        $request->validate([
            'product_id_list' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            $salesAddList = SalesOrderAddList::where("id", $id)->first();
            DemoProductAddOnVoucher::where('product_id_list', $salesAddList->product_id_list)->where('page_name',  "sales_order_addlist")->delete();
            $this->addOnDemoProductStore($request);

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $salesAddList->product_id_list)->get();

            $total_subtotal = 0;
            $total_subtotal =  $for_name->sum('subtotal_on_product');

            $salesAddList->update([
                'date' => $request->date,
                'product_id_list' => $request->product_id_list,
                'account_ledger_id' => $request->account_ledger_id,
                'shipping_details' => $request->shipping_details,
                'delivered_to_details' => $request->delivered_to_details,
                'grand_total' => $total_subtotal
            ]);
        (new LogActivity)->addToLog('Sales Order Add Updated .');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if($request->print){
            return redirect()->action('MBCorporation\SalesController@print_sales_invoice',[ $salesAddList->product_id_list] );
        }

        return redirect()->to('sales_order_addlist');
    }


    public function sale_datatable() 
    {
        $sale_add_list = SalesAddList::with(['ledger', 'demoProducts'])->orderBy('date', 'desc');
        return Datatables::eloquent($sale_add_list)
        ->addIndexColumn()
        ->editColumn('date', function(SalesAddList $sale_add_list) {
            return date('d-m-y', strtotime($sale_add_list->date));
        })
        ->addColumn('ledger_name', function(SalesAddList $sale_add_list) {
            return $sale_add_list->ledger->account_name ?? "";
        })
        ->addColumn('item_details', function(SalesAddList $sale_add_list) {
            return $sale_add_list->demoProducts->pluck('item.name')->implode('<br/>');
        })
        ->addColumn('qty', function(SalesAddList $sale_add_list) {
            return $sale_add_list->demoProducts->pluck('qty')->implode('<br/>');
        })
        ->addColumn('price', function(SalesAddList $sale_add_list) {
            return $sale_add_list->demoProducts->map(function($demo_product) {
                return new_number_format($demo_product->price ?? 0);
            })->implode('<br/>');
        })
        ->addColumn('total_price', function(SalesAddList $sale_add_list) {
            return $sale_add_list->demoProducts->map(function($demo_product) {
                return new_number_format(($demo_product->price ?? 0) * ($demo_product->qty ?? 0));
            })->implode('<br/>');
        })
        ->addColumn('action', function(SalesAddList $sale_add_list) {
            return make_action_btn([
                '<a href="'.route("view_sales", ['product_id_list' => $sale_add_list->id]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_sales",['product_id_list' => $sale_add_list->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="'.route("send_sales_sms", ['product_id_list' => $sale_add_list->id]).'" onclick="alert("'.'"Do You want to send sms?"'.'")" class="dropdown-item"><i class="far fa-envelope"></i> Send SMS</a>',
                '<a href="#" data-id="'.$sale_add_list->product_id_list.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("print_sales_invoice", ['product_id_list' => $sale_add_list->product_id_list]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['ledger_name', 'item_details', 'qty', 'price', 'total_price', 'action'])
        ->make(true);
    }
    
    //add sales addlist .....................
    public function sales_addlist(Request $request)
    {
        if($request->ajax()) {
            return $this->sale_datatable();
        }      
        return view('MBCorporationHome.transaction.sales_addlist.index');
    }
    public function sales_addlist_form()
    {

        $Godwn = Godown::get();
        $account = AccountLedger::get();
        $SaleMen = SaleMen::get();
        $Item = Item::get();
        $SalesOrder = SalesOrderAddList::get();
        return view('MBCorporationHome.transaction.sales_addlist.sales_addlist_form', compact('Godwn', 'account', 'SaleMen', 'Item', 'SalesOrder'));
    }


    public function addOnDemoProductStore($request)
    {
        foreach ($request->item_id as $key => $item) {
            $id_row = rand(111111, 999999).'-'.date('y');
            $data_add = DemoProductAddOnVoucher::create([
                'id_row' => $id_row,
                'product_id_list' => $request->product_id_list,
                'page_name' => $request->page_name,
                'item_id' => $item,
                'price' => $request->price[$key],
                'discount' => $request->discount[$key],
                'qty' => $request->qty[$key],
                'date' => $request->date,
                'subtotal_on_product' => $request->subtotal[$key],
            ]);
        }

        return response()->json($data_add);
    }



    public function SaveAllData_sales(Request $request, Helper $helper)
    {

        $request->validate([
            'product_id_list' => 'required|unique:sales_add_lists',
            'godown_id' => 'required',
            'SaleMen_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            $this->addOnDemoProductStore($request);
            $for_name = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
            // dd($for_name);

            $total_subtotal = 0;
            $total_subtotal =   ($for_name->sum('subtotal_on_product') + $request->other_expense) - $request->discount_total;

            $salesAddList = SalesAddList::create([
                'date' => $request->date,
                'product_id_list' => $request->product_id_list,
                'godown_id' => $request->godown_id,
                'sale_name_id' => $request->SaleMen_name,
                'account_ledger_id' => $request->account_ledger_id,
                'order_no' => $request->order_no,
                'other_bill' => $request->other_expense,
                'expense_ledger_id' => $request->expense_ledger_id,
                'discount_total' => $request->discount_total,
                'pre_amount' => $request->pre_amount,
                'shipping_details' => $request->shipping_details,
                'delivered_to_details' => $request->delivered_to_details,
                'grand_total' => $total_subtotal
            ]);
            // dd($salesAddList);

            foreach ($for_name as $for_name_row) {

                $salesAddList->detailsProduct()->create([
                    'item_id' => $for_name_row->item_id,
                    'qty' =>    $for_name_row->qty,
                ]);

                $old_Stock = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id',  $request->godown_id)->first();

                if ($old_Stock) {
                    $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'sale_price' => $for_name_row->price,
                    ]);
                } else {
                    StockDetail::create([
                        'item_id' => $for_name_row->item_id, 'godown_id' => $request->godown_id,
                        'sales_price' => $for_name_row->sales_price,
                        'qty' => (-1 * $for_name_row->qty)
                    ]);
                }


                // StockHistory
                $stockHistory['item_id'] = $for_name_row->item_id;
                $stockHistory['out_qty'] = $for_name_row->qty;
                $stockHistory['godown_id'] = $request->godown_id;
                $stockHistory['category_id'] = $for_name_row->item->category->id;
                $stockHistory['average_price'] = $for_name_row->price ;
                $salesAddList->stock()->create($stockHistory);

                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                if ($itemCount) {
                    $itemCount->update(['sell_qty' => $for_name_row->qty + $itemCount->sell_qty]);
                } else {
                    ItemCount::updateOrCreate(['item_id' => $for_name_row->item_id], ['sell_qty' => $for_name_row->qty ]);
                }

            };

            $account_ledger = AccountLedger::where('id', $request->account_ledger_id)->first();

            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->account_ledger_id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->product_id_list,
                'debit' => $total_subtotal,
                'date' => $request->date,
            ]);

            $salesAddList->transaction()->updateOrCreate(
                ['vo_no' => $request->product_id_list, 'ledger_id' => $request->account_ledger_id],
                [
                    'date' => $request->date,
                    'ledger_id' => $request->account_ledger_id,
                    'debit' => $total_subtotal
                ]
            );
            // $summary = LedgerSummary::where('ledger_id', $request->account_ledger_id)->first();
            $summary = LedgerSummary::where('ledger_id', $request->account_ledger_id)
            ->where('financial_date', (new Helper)::activeYear())->first();

            if ($summary) {
                $summary->update(['debit' => $total_subtotal + $summary->debit]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->account_ledger_id, 'financial_date' => (new Helper)::activeYear()], [
                    'debit' => $total_subtotal
                ]);
            }
            // Others Expense
            if($request->expense_ledger_id >0 && $request->other_expense >0){
                $expens_ledger = AccountLedger::where('id', $request->expense_ledger_id)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $expens_ledger->id,
                    'account_ledger_id' => $expens_ledger->account_ledger_id,
                    'account_name' => $expens_ledger->account_name,
                    'account_ledger__transaction_id' => $request->product_id_list,
                    'credit' => $request->other_expense,
                    'date' => $request->date,
                ]);

                $salesAddList->transaction()->updateOrCreate(
                    ['vo_no' => $request->product_id_list, 'ledger_id' => $request->expense_ledger_id],
                    [
                        'date' => $request->date,
                        'ledger_id' => $request->expense_ledger_id,
                        'credit' => $request->other_expense
                    ]
                );
                $summaryX = LedgerSummary::where('ledger_id', $request->expense_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())->first();

                if ($summaryX) {
                    $summaryX->update(['credit' => $request->other_expense + $summaryX->credit]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->expense_ledger_id, 'financial_date' => (new Helper)::activeYear()], [
                        'credit' => $request->other_expense
                    ]);
                }
            }

            // send sms
            if($request->has('send_sms') && $request->send_sms == 'yes') {
                $this->send_sales_sms($salesAddList->id);
            }
        
        (new LogActivity)->addToLog('Sales Add Created .');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            //throw $th;
            return response()->json($ex->getMessage());
        }
        if($request->print){
            return redirect()->action('MBCorporation\SalesController@print_sales_invoice',[ $salesAddList->product_id_list] );
        }
        return redirect()->to('sales_addlist');
    }

    public function edit_sales($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        $salesAddList = SalesAddList::whereId($id)->with(['ledger', 'godown', 'saleMen'])->first();
        $godown = Godown::get();
        $accounts = AccountLedger::get();
        $saleMen = SaleMen::get();
        $items = Item::get();
        $expense_ledgers = AccountLedger::whereIn('account_group_id', [9,11])->get(['id', 'account_name']);
        return view('MBCorporationHome.transaction.sales_addlist.edit_sales_addlist', compact('godown', 'accounts', 'saleMen', 'items', 'salesAddList', 'expense_ledgers'));
    }

    public function UpdateSalesAddList(Request $request,Helper $helper, $id)
    {
        $request->validate([
            'product_id_list' => 'required',
            'godown_id' => 'required',
            'SaleMen_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            
            StockHistory::where('stockable_type', 'App\SalesAddList')
            ->where('stockable_id', $id)
            ->Update(['date' => $request->date]);
            
            $this->deleteDemoProductAddOnVoucher($request,  $id, 'App\SalesAddList');
            $salesAddList = SalesAddList::where("id", $id)->first();

            if($request->new_item_id){
                $this->addOnDemoProductUpdateStore($request,  $salesAddList );
            }

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $salesAddList->product_id_list)->get();


            $total_subtotal = 0;
            $total_subtotal =  ($for_name->sum('subtotal_on_product') + $request->other_expense) - $request->discount_total;

            AccountLedgerTransaction::where('account_ledger__transaction_id', $salesAddList->product_id_list)->where("ledger_id", $request->account_ledger_id)->Update([
                'credit' => 0,
                'debit' => $total_subtotal,
                'date' => $request->date,
            ]);
            
            
            if($request->has('expense_ledger_id') && $request->expense_ledger_id != 0){
                //other_expense
                $expens_ledger = AccountLedger::where('id', $request->expense_ledger_id)->first();
               
                AccountLedgerTransaction::updateOrCreate(
                    [
                        'account_ledger__transaction_id' => $salesAddList->product_id_list,
                        'ledger_id' => $request->expense_ledger_id,
                    ],
                    [
                        'credit' => $request->other_expense,
                        'debit' => 0,
                        'date' => $request->date,
                        'account_ledger_id' => $expens_ledger->account_ledger_id,
                        'account_name' => $expens_ledger->account_name,
                    ]
                );
            }


            Transaction::updateOrCreate(
                ['vo_no' => $request->product_id_list, 'transactionable_id' => $salesAddList->id, 'ledger_id' => $request->account_ledger_id],
                [
                    'date' => $request->date,
                    'ledger_id' => $request->account_ledger_id,
                    'debit' => $total_subtotal
                ]
            );
            
            if($salesAddList->grand_total !=  $total_subtotal){
                $summary = LedgerSummary::where('ledger_id' ,$request->account_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
                if($summary){
                    $summary->update(['debit' =>$total_subtotal + $summary->debit - $salesAddList->grand_total ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_ledger_id, 'financial_date' => (new Helper)::activeYear()],[
                            'debit' => $total_subtotal
                        ]);
                }
            }
            
            //other_expense
            if($request->expense_ledger_id > 0 ){
                if($request->expense_ledger_id == $salesAddList->expense_ledger_id){
                    Transaction::updateOrCreate(
                        ['vo_no' => $request->product_id_list, 'transactionable_id' => $salesAddList->id, 'ledger_id' => $salesAddList->expense_ledger_id],
                        [
                            'date' => $request->date,
                            'ledger_id' => $request->expense_ledger_id,
                            'credit' => $request->other_expense
                        ]
                    );
        
                    
                    //other_expense
                    if($salesAddList->other_bill !=  $request->other_expense){
                        $summary = LedgerSummary::where('ledger_id' ,$request->expense_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
                        if($summary){
                            $summary->update(['credit' =>$request->other_expense + $summary->credit - $salesAddList->other_bill ]);
                        }else{
                            LedgerSummary::updateOrCreate(['ledger_id' =>$request->expense_ledger_id, 'financial_date' => (new Helper)::activeYear()],[
                                    'credit' => $request->other_expense
                                ]);
                        }
                    }
                }else if($request->expense_ledger_id != $salesAddList->expense_ledger_id){
                    // ***** Del Previous ledger
                     $summary = LedgerSummary::where('ledger_id' ,$salesAddList->expense_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
                    if($summary) {
                        $summary->update(['credit' => $summary->credit - $salesAddList->other_bill ]);
                        
                        // transaction delete
                        Transaction::where([
                            'vo_no' => $salesAddList->product_id_list, 
                            'transactionable_id' => $salesAddList->id, 
                            'ledger_id' => $salesAddList->expense_ledger_id
                        ])->delete();
                            
                        // account ledger transaction delete
                        AccountLedgerTransaction::where('account_ledger__transaction_id', $salesAddList->product_id_list)
                        ->where("ledger_id", $salesAddList->expense_ledger_id)->delete();
                    }
                    
                    //****** Again Create Ledger
                    
                    Transaction::create([
                        'vo_no' => $request->product_id_list, 
                        'transactionable_id' => $salesAddList->id, 
                        'date' => $request->date,
                        'ledger_id' => $request->expense_ledger_id,
                        'credit' => $request->other_expense,
                    ]);
        
                    
                    $summary = LedgerSummary::where('ledger_id' ,$request->expense_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
                    if($summary){
                        $summary->update(['credit' =>$request->other_expense + $summary->credit - $salesAddList->other_bill ]);
                    }else{
                        LedgerSummary::updateOrCreate(['ledger_id' =>$request->expense_ledger_id, 'financial_date' => (new Helper)::activeYear()],[
                                'credit' => $request->other_expense
                        ]);
                    }
                }
                
            } else {
                // update ledger summary
                $summary = LedgerSummary::where('ledger_id' ,$salesAddList->expense_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
                if($summary) {
                    $summary->update(['credit' => $summary->credit - $salesAddList->other_bill ]);
                    
                    // transaction delete
                    Transaction::where([
                        'vo_no' => $salesAddList->product_id_list, 
                        'transactionable_id' => $salesAddList->id, 
                        'ledger_id' => $salesAddList->expense_ledger_id
                    ])->delete();
                        
                    // account ledger transaction delete
                    AccountLedgerTransaction::where('account_ledger__transaction_id', $salesAddList->product_id_list)
                    ->where("ledger_id", $salesAddList->expense_ledger_id)->delete();
                }
            }
            
            $salesAddList->update([
                'date' => $request->date,
                'product_id_list' => $request->product_id_list,
                'godown_id' => $request->godown_id,
                'sale_name_id' => $request->SaleMen_name,
                'account_ledger_id' => $request->account_ledger_id,
                'order_no' => $request->order_no,
                'other_bill' => $request->other_expense,
                'expense_ledger_id' => $request->expense_ledger_id,
                'discount_total' => $request->discount_total,
                'pre_amount' => $request->pre_amount,
                'shipping_details' => $request->shipping_details,
                'delivered_to_details' => $request->delivered_to_details,
                'grand_total' => $total_subtotal
            ]);
        (new LogActivity)->addToLog('Sales Add Updated .');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if($request->print){
            return redirect()->action('MBCorporation\SalesController@print_sales_invoice',[ $salesAddList->product_id_list] );
        }

        return redirect()->to('sales_addlist');
    }

    public function addOnDemoProductUpdateStore($request,  $salesAddList)
    {
        foreach ($request->new_item_id as $key=>$for_item_row) {
            $id_row = rand(111111, 999999).'-'.date('y');
            $data_add = DemoProductAddOnVoucher::create([
                'id_row' => $id_row,
                'product_id_list' => $request->product_id_list,
                'page_name' => $request->page_name,
                'item_id' => $for_item_row,
                'price' =>$request->new_price[$key],
                'discount' => $request->new_discount[$key],
                'qty' => $request->new_qty[$key],
                'date' => $request->date,
                'subtotal_on_product' =>  $request->new_subtotal[$key] ,
            ]);

            //salesAddList
            $salesAddList->detailsProduct()->create([
                'item_id' => $data_add->item_id,
                'qty' =>    $data_add->qty,
            ]);

            $old_Stock = StockDetail::where('item_id', $data_add->item_id)->where('godown_id',  $request->godown_id)->first();

            if ($old_Stock) {
                $new_stock_qty = $old_Stock->qty - $data_add->qty;
                $old_Stock->update([
                    'qty' => $new_stock_qty,
                    'sale_price' => $data_add->price,
                ]);
            } else {
                StockDetail::create([
                    'item_id' => $data_add->item_id, 'godown_id' => $request->godown_id,
                    'sales_price' => $data_add->sales_price,
                    'qty' => (-1 * $data_add->qty)
                ]);
            }


            // StockHistory
            $stockHistory['item_id'] = $data_add->item_id;
            $stockHistory['out_qty'] = $data_add->qty;
            $stockHistory['godown_id'] = $request->godown_id;
            $stockHistory['category_id'] = $data_add->item->category->id;
            $stockHistory['average_price'] = $data_add->price ;
            $salesAddList->stock()->create($stockHistory);

            $itemCount = ItemCount::where('item_id', $data_add->item_id)->first();
            if ($itemCount) {
                $itemCount->update(['sell_qty' => $data_add->qty + $itemCount->sell_qty]);
            } else {
                ItemCount::updateOrCreate(['item_id' => $data_add->item_id], ['sell_qty' => $data_add->qty ]);
            }


        }
    }

    public function delete_sales($product_id_list)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        try {
            DB::beginTransaction();

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $product_id_list)->get();
            $purchases_row = SalesAddList::where('product_id_list', $product_id_list)->first();
            foreach ($for_name as $for_name_row) {
                    $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->get();
                    foreach ($old_Stock_qty as $old_Stock_qty_row) {
                        $new_stock_qty = $old_Stock_qty_row->qty + $for_name_row->qty;
                        StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->update([
                            'qty' => $new_stock_qty,
                        ]);
                    };
                    Transaction::where('vo_no', $purchases_row->product_id_list)->where('transactionable_id', $purchases_row->id)
                    ->delete();

                    $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();

                    if ($itemCount) {
                        $itemCount->update(['sale_qty' =>  $itemCount['sale_qty'] - $for_name_row['qty'] ]);
                    }
                    StockHistory::where('stockable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                    ->where('out_qty',$for_name_row->qty )
                    ->where('stockable_type', 'App\SalesAddList')->delete();

                    $for_name_row->delete();

            }
            $summary = LedgerSummary::where('ledger_id' ,$purchases_row->account_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
            if($summary){
                $summary->update(['debit' =>  $summary->debit - $purchases_row->grand_total ]);
            }
            
            $summaryExpense = LedgerSummary::where('ledger_id' ,$purchases_row->expense_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
            if($summaryExpense){
                $summaryExpense->update(['credit' =>  abs($summaryExpense->credit - $purchases_row->other_bill) ]);
            }
            
            AccountLedgerTransaction::where('account_ledger__transaction_id', $product_id_list)->delete();

            $purchases_row->delete();
            (new LogActivity)->addToLog('Sales Add Deleted .');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->to('sales_addlist');

    }
    
    public function view_sales_order($product_id_list)
    {
        return view('MBCorporationHome.transaction.sales_order_addlist.view_sales_order', compact('product_id_list'));
    }
    public function print_sales_order_invoice($product_id_list)
    {
        return view('MBCorporationHome.transaction.sales_order_addlist.print_sales_order_invoice', compact('product_id_list'));
    }

    public function view_sales($product_id_list)
    {
        return view('MBCorporationHome.transaction.sales_addlist.view_sales', compact('product_id_list'));
    }

    public function print_sales_invoice($product_id_list)
    {
        return view('MBCorporationHome.transaction.sales_addlist.print_sales_invoice', compact('product_id_list'));
    }
 public function print_sales_invoice2($product_id_list)
    {
        return view('MBCorporationHome.transaction.sales_addlist.print_sales_invoice_2', compact('product_id_list'));
    }

    public function sale_return_datatable()
    {
        $sale_return_lists = SalesReturnAddList::with(['ledger', 'demoProducts'])->orderBy('date', 'desc');
        return Datatables()->eloquent($sale_return_lists)
        ->addIndexColumn()
        ->addColumn('ledger_name', function(SalesReturnAddList $sale_return_list) {
            return $sale_return_list->ledger->account_name ?? "";
        })
        ->addColumn('item_details', function(SalesReturnAddList $sale_return_list) {
            return $sale_return_list->demoProducts->pluck('item.name')->implode('<br/>');
        })
        ->addColumn('qty', function(SalesReturnAddList $sale_return_list) {
            return $sale_return_list->demoProducts->pluck('qty')->implode('<br/>');
        })        
        ->addColumn('price', function(SalesReturnAddList $sale_return_list) {
            return $sale_return_list->demoProducts->map(function($demo_product){
                return new_number_format(($demo_product->price ?? 0));
            })->implode('<br/>');
        })
        ->addColumn('total_price', function(SalesReturnAddList $sale_return_list) {
            return $sale_return_list->demoProducts->map(function($demo_product){
                return new_number_format(($demo_product->qty ?? 0) * ($demo_product->price));
            })->implode('<br/>');
        })
        ->addColumn('action', function(SalesReturnAddList $sale_return_list) {
            return make_action_btn([
                '<a href="'.route("edit_sales_return", ['product_id_list' => $sale_return_list->id]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_sales_return",['product_id_list' => $sale_return_list->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="'.route("send_sales_return_sms", ['product_id_list' => $sale_return_list->id]).'" class="dropdown-item"><i class="far fa-envelope"></i> SMS</a>',
                '<a href="javascript:void(0)" data-id="'.$sale_return_list->product_id_list.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("print_sales_return_invoice", ['product_id_list' => $sale_return_list->id]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['action', 'item_details', 'qty', 'total_price', 'price'])
        ->make(true);
    }

    public function salesreturn_addlist(Request $request)
    {
        if($request->ajax()) {
            return $this->sale_return_datatable();
        }
        $PurchasesAddList = SalesReturnAddList::with(['ledger'])->get();
        return view('MBCorporationHome.transaction.salesreturn_addlist.index', compact('PurchasesAddList'));
    }
    public function salesreturn_addlist_form()
    {
        $godowns = Godown::get();
        $accounts = AccountLedger::get();
        $saleMens = SaleMen::get();
        $items = Item::get();
        return view('MBCorporationHome.transaction.salesreturn_addlist.sales_return_addlist_form', compact('godowns', 'accounts', 'saleMens', 'items'));
    }
    public function print_sales_return_invoice($product_id_list)
    {
        return view('MBCorporationHome.transaction.salesreturn_addlist.print_sales_invoice', compact('product_id_list'));
    }

    public function SaveAllData_sales_return_store(Request $request, Helper $helper)
    {

        $request->validate([
            'product_id_list' => 'required|unique:sales_return_add_lists',
            'godown_id' => 'required',
            'SaleMen_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from
        ]);
        try {
            DB::beginTransaction();
            $this->addOnDemoProductStore($request);
            $total_subtotal = 0;

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
            $total_subtotal =  ($for_name->sum('subtotal_on_product') + $request->other_bill) - $request->discount_total;

            $salesReturnAddList = SalesReturnAddList::create([
                'date' => $request->date,
                'product_id_list' => $request->product_id_list,
                'godown_id' => $request->godown_id,
                'sale_man_id' => $request->SaleMen_name,
                'account_ledger_id' => $request->account_ledger_id,
                'order_no' => $request->order_no,
                'other_bill' => $request->other_bill,
                'discount_total' => $request->discount_total,
                'pre_amount' => $request->pre_amount,
                'shipping_details' => $request->shipping_details,
                'delivered_to_details' => $request->delivered_to_details,
                'grand_total' => $total_subtotal,
            ]);


            foreach ($for_name as $for_name_row) {

                // salesReturnAddList
                $salesReturnAddList->detailsProduct()->create([
                    'item_id' => $for_name_row->item_id,
                    'qty' =>    $for_name_row->qty,
                ]);

                // stock
                $old_Stock = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $request->godown_id)->first();

                if ($old_Stock) {
                    $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'sale_price' => $for_name_row->price,
                    ]);
                } else {
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $request->godown_id;
                    $stock['qty'] = (-1 * $for_name_row->qty);
                    $stock['price'] =  $for_name_row->sales_price;
                    StockDetail::create($stock);
                }
                 // StockHistory
                $stockHistory['item_id'] = $for_name_row->item_id;
                $stockHistory['in_qty'] = $for_name_row->qty;
                $stockHistory['godown_id'] = $request->godown_id;
                $stockHistory['category_id'] = $for_name_row->item->category->id;
                $stockHistory['average_price'] = $for_name_row->price ;
                $salesReturnAddList->stock()->create($stockHistory);

                // ItemCount
                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                if ($itemCount) {
                    $itemCount->update(['sell_return_qty' =>  $itemCount->sell_return_qty + $for_name_row->qty]);
                } else {
                    ItemCount::updateOrCreate(['item_id' => $for_name_row->item_id], ['sell_return_qty' => $for_name_row->qty ]);
                }
            }

            $account_ledger = AccountLedger::where('id', $request->account_ledger_id)->first();

            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->account_ledger_id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->product_id_list,
                'credit' => $total_subtotal,
            ]);

            $salesReturnAddList->transaction()->updateOrCreate(
                ['vo_no' => $request->product_id_list],
                [
                    'date' => $request->date,
                    'ledger_id' => $request->account_ledger_id,
                    'credit' => $total_subtotal
                ]
            );

            $summary = LedgerSummary::where('ledger_id', $request->account_ledger_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['credit' => $total_subtotal + $summary->credit]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->account_ledger_id, 'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $total_subtotal
                ]);
            }
            (new LogActivity)->addToLog('Sales Return Created.');


            if($request->print){
                $product_id_list= $salesReturnAddList->product_id_list;
                return view('MBCorporationHome.transaction.salesreturn_addlist.print_sales_invoice', compact('product_id_list'));
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json($ex->getMessage());
        }

        return  redirect()->to('salesreturn_addlist');
    }

    public function edit_sales_return($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        
        $godown = Godown::get();
        $accounts = AccountLedger::get();
        $SaleMen = SaleMen::get();
        $items = Item::get();
        $salesReturnAddList = SalesReturnAddList::where('id', $id)->first();
        return view('MBCorporationHome.transaction.salesreturn_addlist.edit_sales_return_addlist', compact('godown', 'accounts', 'SaleMen', 'items', 'salesReturnAddList'));
    }

    public function UpdateSalesReturnaddlist(Request $request,Helper $helper, $id)
    {

        $request->validate([
            'product_id_list' => 'required',
            'godown_id' => 'required',
            //'SaleMan_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from
        ]);


        try {
            DB::beginTransaction();
            $this->deleteDemoProductAddOnVoucher($request,  $id, 'App\SalesReturnAddList');
            $saleReturnDetails = SalesReturnAddList::where('id', $id)->with('ledger', 'saleMen', 'godown')->first();
            if($request->new_item_id){
               $this->addOnDemoProductReturnUpdateStore($request,  $saleReturnDetails);
            }
            $total_subtotal = 0;

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $saleReturnDetails->product_id_list)->get();


            $total_subtotal = $for_name->sum('subtotal_on_product')  + $request->other_bill - $request->discount_total;
            AccountLedgerTransaction::where('account_ledger__transaction_id', $saleReturnDetails->product_id_list)->Update([
                'credit' => $total_subtotal,
                'debit' => 0,
            ]);




            Transaction::updateOrCreate(
                ['vo_no' => $request->product_id_list, 'transactionable_id' => $saleReturnDetails->id],
                [
                    'date' => $request->date,
                    'ledger_id' => $saleReturnDetails->account_ledger_id,
                    'credit' => $total_subtotal
                ]
            );

            if($saleReturnDetails->grand_total !=  $total_subtotal){
                $summary = LedgerSummary::where('ledger_id' ,$request->account_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                //echo $saleReturnDetails->grand_total;
                if($summary){
                    $summary->update(['credit' => abs($summary->credit - $saleReturnDetails->grand_total) + $total_subtotal ]);

                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_ledger_id, 'financial_date'=> (new Helper)::activeYear()],[
                            'credit' => $total_subtotal
                        ]);
                }
            }
            $saleReturnDetails->update([
                'date'                  => $request->date,
                'product_id_list'       => $request->product_id_list,
                'godown_id'             => $request->godown_id,
                'sale_name_id'          => $request->SaleMan_name,
                'account_ledger_id'     => $saleReturnDetails->account_ledger_id,
                'order_no'              => $request->order_no,
                'other_bill'            => $request->other_bill,
                'discount_total'        => $request->discount_total,
                'pre_amount'            => $request->pre_amount,
                'shipping_details'      => $request->shipping_details,
                'delivered_to_details'  => $request->delivered_to_details,
                'grand_total'           => $total_subtotal,
            ]);

            (new LogActivity)->addToLog('Sales Return Updated.');
            if($request->print){
                $product_id_list= $saleReturnDetails->product_id_list;
                return view('MBCorporationHome.transaction.salesreturn_addlist.print_sales_invoice', compact('product_id_list'));
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }


        return redirect()->to('salesreturn_addlist');

    }

    public function addOnDemoProductReturnUpdateStore($request, $saleReturnDetails)
    {
        foreach ($request->new_item_id as $key=>$for_item_row) {
            $id_row = rand(111111, 999999).'-'.date('y');
            $data_add = DemoProductAddOnVoucher::create([
                'id_row' => $id_row,
                'product_id_list' => $request->product_id_list,
                'page_name' => $request->page_name,
                'item_id' => $for_item_row,
                'price' =>$request->new_price[$key],
                'discount' => $request->new_discount[$key],
                'qty' => $request->new_qty[$key],
                'date' => $request->date,
                'subtotal_on_product' =>  $request->new_subtotal[$key] ,
            ]);

            //salesAddList
            $saleReturnDetails->detailsProduct()->create([
                'item_id' => $data_add->item_id,
                'qty' =>    $data_add->qty,
            ]);

            $old_Stock = StockDetail::where('item_id', $data_add->item_id)->where('godown_id',  $request->godown_id)->first();

            if ($old_Stock) {
                $new_stock_qty = $old_Stock->qty + $data_add->qty;
                $old_Stock->update([
                    'qty' => $new_stock_qty,
                    'sale_price' => $data_add->price,
                ]);
            } else {
                StockDetail::create([
                    'item_id' => $data_add->item_id, 'godown_id' => $request->godown_id,
                    'sales_price' => $data_add->sales_price,
                    'qty' => (1 * $data_add->qty)
                ]);
            }


            // StockHistory
            $stockHistory['item_id'] = $data_add->item_id;
            $stockHistory['in_qty'] = $data_add->qty;
            $stockHistory['godown_id'] = $request->godown_id;
            $stockHistory['category_id'] = $data_add->item->category->id;
            $stockHistory['average_price'] = $data_add->price ;
            $saleReturnDetails->stock()->create($stockHistory);

            $itemCount = ItemCount::where('item_id', $data_add->item_id)->first();
            if ($itemCount) {
                $itemCount->update(['sell_return_qty' => $data_add->qty + $itemCount->sell_return_qty]);

            }else {
                ItemCount::updateOrCreate(['item_id' => $data_add->item_id], ['sell_return_qty' => $data_add->qty ]);
            }

        }
    }

    public function delete_sales_return($product_id_list)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        try {
            DB::beginTransaction();

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $product_id_list)->get();

            $purchases_row = SalesReturnAddList::where('product_id_list', $product_id_list)->first();
            foreach ($for_name as $for_name_row) {


                    $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->get();

                    foreach ($old_Stock_qty as $old_Stock_qty_row) {
                        $new_stock_qty = $old_Stock_qty_row->qty - $for_name_row->qty;
                        StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->update([
                            'qty' => $new_stock_qty,
                        ]);

                    };
                    Transaction::where('vo_no', $purchases_row->product_id_list)
                    ->where('transactionable_type', 'App\SalesReturnAddList')
                    ->where('transactionable_id', $purchases_row->id)
                    ->delete();


                    $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();

                    if ( $itemCount['sell_return_qty'] > 0) {
                        $itemCount->update(['sell_return_qty' =>  $itemCount['sell_return_qty'] - $for_name_row['qty'] ]);
                    }else{
                        $itemCount->update(['sell_return_qty' =>  $itemCount['sell_return_qty'] + $for_name_row['qty'] ]);
                    }
                    $StockHistory =StockHistory::where([
                        ['item_id' , $for_name_row->item_id],['godown_id', $purchases_row->godown_id],['stockable_type',  'App\SalesReturnAddList'],
                        ['stockable_id' , $purchases_row->id],
                        ['in_qty',  $for_name_row->qty]
                    ])->first();
                    $StockHistory->delete();


                $for_name_row->delete();
            };

            AccountLedgerTransaction::where('account_ledger__transaction_id', $product_id_list)->delete();
            $summary = LedgerSummary::where('ledger_id' ,$purchases_row->account_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();

            if($summary){
                $summary->update(['credit' =>  abs($summary->credit - $purchases_row->grand_total) ]);
            }
            SalesReturnAddList::where('product_id_list', $product_id_list)->delete();

            (new LogActivity)->addToLog('Sales Return Deleted.');

            DB::commit();
        } catch (\Exception $ex) {
            return response()->json(['mes' => $ex->getMessage(), 'status' => false]);

            DB::rollBack();

            dd($ex->getMessage(), $ex->getLine());
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->back();
    }


    public function deleteDemoProductAddOnVoucher($request, $id, $type )
    {

        $productsId =[];
        if($type === 'App\SalesAddList'){

            $purchases_row = SalesAddList::where('product_id_list',  $request->product_id_list)->with('stock')->first();
        }else{
            $purchases_row = SalesReturnAddList::where('product_id_list',  $request->product_id_list)->with('stock')->first();

        }

        $allProducts = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get(['item_id'])->toArray();
        $demoProducts = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
        if($request->item_id){
            foreach ($allProducts as $key => $value) {
                array_push($productsId, $value['item_id']);
            }
            $deletedProducts = array_diff($productsId, $request->item_id);
            if(count($deletedProducts) > 0 && $deletedProducts != null){
                foreach ($deletedProducts as $key => $value) {
                    $oldQty = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->where('item_id', $value)->first();
                    SaleDetails::where('saleable_id', $id)->where('item_id',  $value)->where('saleable_type', $type)->delete();
                    $old_Stock = StockDetail::where('item_id',  $value)->where('godown_id', $request->godown_id)->first();


                    $itemCount = ItemCount::where('item_id', $value)->first();
                    if($type === 'App\SalesAddList'){

                        $StockHistory = StockHistory::where([
                            ['item_id' , $value],['godown_id', $request->godown_id],['stockable_type',  $type],['stockable_id' , $id],
                            ['out_qty',  $oldQty->qty]
                        ])->first();

                        if( $StockHistory->out_qty < $oldQty->qty){
                            $old_Stock->update(['qty' =>$old_Stock->qty - $oldQty->qty ]);
                        }

                        $StockHistory->delete();
                        if( $itemCount['sell_qty'] >= 0){
                            $itemCount->update(['sell_qty' => $itemCount['sell_qty']  - $oldQty['qty']]);
                        }else{
                            $itemCount->update(['sell_qty' => $itemCount['sell_qty']  + $oldQty['qty']]);

                        }
                    }else{
                        $StockHistory = StockHistory::where([
                            ['item_id' , $value],['godown_id', $request->godown_id],['stockable_type',  $type],['stockable_id' , $id],
                            ['in_qty',  $oldQty->qty]
                        ])->first();
                        if( $StockHistory->in_qty < $oldQty->qty){
                            $old_Stock->update(['qty' =>$old_Stock->qty + $oldQty->qty ]);
                        }

                        $StockHistory->delete();

                        if($itemCount['sell_return_qty'] >= 0){
                            $itemCount->update(['sell_return_qty' => $itemCount['sell_return_qty']  - $oldQty['qty']]);
                        }else{
                            $itemCount->update(['sell_return_qty' => $itemCount['sell_return_qty']  + $oldQty['qty']]);
                        }
                    }
                    $oldQty->delete();
                }
            }
        }else{
            if($type == "App\SalesAddList"){
                foreach ($demoProducts as $for_name_row) {

                        $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->first();
                        $StockHistory =StockHistory::where([
                            ['item_id' , $for_name_row->item_id],['godown_id', $purchases_row->godown_id],['stockable_type',  $type],
                            ['stockable_id' , $id],
                            ['out_qty',  $for_name_row->qty]
                        ])->first();

                        if( $StockHistory->out_qty < $for_name_row->qty){
                            $new_stock_qty = $old_Stock_qty->qty - $for_name_row->qty;
                            $old_Stock_qty->update([
                                'qty' => $new_stock_qty,
                            ]);
                        }

                        $StockHistory->delete();

                        $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                        if ($itemCount['sell_qty'] >= 0) {
                            $itemCount->update(['sell_qty' =>  $itemCount['sell_qty'] - $for_name_row['qty'] ]);
                        }else{
                            $itemCount->update(['sell_qty' =>  $itemCount['sell_qty'] + $for_name_row['qty'] ]);

                        }

                        SaleDetails::where('saleable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                        ->where('qty',  $for_name_row->qty)
                        ->where('saleable_type', $type)->delete();
                        $for_name_row->delete();

                }

            }else{
                foreach ($demoProducts as $for_name_row) {

                        $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->first();

                        $StockHistory =StockHistory::where([
                            ['item_id' , $for_name_row->item_id],['godown_id', $purchases_row->godown_id],['stockable_type',  $type],['stockable_id' , $id],
                            ['in_qty',  $for_name_row->qty]
                        ])->first();


                        if( $StockHistory->in_qty < $for_name_row->qty){
                            $new_stock_qty = $old_Stock_qty->qty + $for_name_row->qty;
                            $old_Stock_qty->update([
                                'qty' => $new_stock_qty,
                            ]);
                        }
                        $StockHistory->delete();

                        $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();

                        if ( $itemCount['sale_return_qty'] >= 0) {
                            $itemCount->update(['sale_return_qty' =>  $itemCount['sale_return_qty'] - $for_name_row['qty'] ]);
                        }else{
                            $itemCount->update(['sale_return_qty' =>  $itemCount['sale_return_qty'] + $for_name_row['qty'] ]);
                        }

                        SaleDetails::where('saleable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                        ->where('qty',  $for_name_row->qty)->where('saleable_type', $type)->delete();
                        $for_name_row->delete();

                }


            }
        }


    }
    
    public function sales_order_approved($id='', $md_signature='')
    {
        
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        
        $info = SalesOrderAddList::where('id', $id)->first();
        $status = 1;
        $info->update(['md_signature'=>$status]);
        return redirect()->back();
    }
    

}
