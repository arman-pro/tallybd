<?php

namespace App\Http\Controllers\MbCOrporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Godown;
use App\AccountLedger;
use App\Item;
use App\DemoProductAddOnVoucher;
use App\AccountLedgerTransaction;
use App\Companydetail;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\ItemCount;
use App\LedgerSummary;
use App\PurchaseDetails;
use App\PurchasesAddList;
use App\PurchasesReturnAddList;
use App\SaleMen;
use App\StockDetail;
use App\StockHistory;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use App\Helpers\Product;
use App\Traits\SMS;
use Illuminate\Contracts\Validation\Rule;
use Session;
use DataTables;

class PurchasesController extends Controller
{
    use SMS;
    
    public function datatables() 
    {      
      
        $purchases_add_list = PurchasesAddList::with(['ledger', 'demoProducts'])
        ->orderBy('date', 'desc');
        return DataTables::eloquent($purchases_add_list)
        ->addIndexColumn()
        ->addColumn('date', function(PurchasesAddList $purchases_add_list) {
            return date('d-m-y', strtotime($purchases_add_list->date));
        })
        ->addColumn('ledger_name', function(PurchasesAddList $purchases_add_list) {
            return optional($purchases_add_list->ledger)->account_name ?? '-';
        })
        ->addColumn('item_details', function(PurchasesAddList $purchases_add_list) {
            return $purchases_add_list->demoProducts->pluck('item.name')->implode('<br/>');
        })
        ->addColumn("qty", function(PurchasesAddList $purchases_add_list) {
            return $purchases_add_list->demoProducts->pluck('qty')->implode('<br/>');
        })
        ->addColumn('price', function(PurchasesAddList $purchases_add_list) {
            return $purchases_add_list->demoProducts->map(function($demo_product){
                return new_number_format($demo_product->price);
            })->implode('<br/>');
        })
        ->addColumn('total_price', function(PurchasesAddList $purchases_add_list) {
            return $purchases_add_list->demoProducts->map(function($demo_product){
                return new_number_format($demo_product->price * $demo_product->qty);
            })->implode('<br/>');
        })
        ->addColumn('action', function(PurchasesAddList $purchases_add_list) {
            return make_action_btn([
                '<a href="'.route("view_purchases", ['product_id_list' => $purchases_add_list->id]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_purchases",['product_id_list' => $purchases_add_list->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="#" data-id="'.$purchases_add_list->product_id_list.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("print_pruchases_invoice", ['product_id_list' => $purchases_add_list->product_id_list]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['item_details', 'qty', 'price', 'total_price', 'action'])
        ->make(true);
    }
    //add purchases .....................
    public function purchases_addlist_list()
    {
        if(request()->ajax()) {
            return $this->datatables();
        }
        return view('MBCorporationHome.transaction.purchases_addlist.index');
    }

    public function purchases_addlist_from()
    {
        $godown     = Godown::get();
        $account    = AccountLedger::get();
        $SaleMan    = SaleMen::get();
        $Item       = Item::get();

        return view('MBCorporationHome.transaction.purchases_addlist.purchases_addlist_form', compact('godown', 'account', 'SaleMan', 'Item'));
    }
    public function SaveAllData_store(Request $request, Helper $helper)
    {

        $request->validate([
            'product_id_list' => 'required|unique:purchases_add_lists',
            'godown_id' => 'required',
            'SaleMan_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();

            $total_subtotal = 0;
            $this->addOnDemoProductStore($request);
            $for_name = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
            $total_subtotal =  ($for_name->sum('subtotal_on_product') + $request->other_expense) - $request->discount_total;

            // PurchasesAddList
            $purchasesData = PurchasesAddList::create([
                'date' => $request->date,
                'product_id_list' => $request->product_id_list,
                'godown_id' => $request->godown_id,
                'sale_name_id' => $request->SaleMan_name,
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
            // return $purchasesData;
            foreach ($for_name as $for_name_row) {

                // purchasesDetails
                  $purchasesData->detailsProduct()->create([
                    'item_id' => $for_name_row->item_id,
                    'qty' =>    $for_name_row->qty,
                ]);

                // stock
                $old_Stock = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $request->godown_id)->first();
                if ($old_Stock) {
                    $new_stock_qty = $old_Stock->qty + $for_name_row->qty;

                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'purchases_price' => $for_name_row->price,
                    ]);
                } else {
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $request->godown_id;
                    $stock['qty'] = $for_name_row->qty;
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }
               


                 // StockHistory
                $stockHistory['item_id'] = $for_name_row->item_id;
                $stockHistory['in_qty'] = $for_name_row->qty;
                $stockHistory['godown_id'] = $request->godown_id;
                $stockHistory['category_id'] = $for_name_row->item->category->id;
                $stockHistory['average_price'] = $for_name_row->price ;
                $purchasesData->stock()->create($stockHistory);
                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                if ($itemCount) {
                    $itemCount->update(['purchase_qty' => $for_name_row->qty + $itemCount->purchase_qty
                    ]
                );
                } else {
                   ItemCount::updateOrCreate(['item_id' => $for_name_row->item_id], ['purchase_qty' =>$for_name_row->qty ]);
                }
            }

            $account_ledger = AccountLedger::where('id', $request->account_ledger_id)->first();

            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->account_ledger_id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->product_id_list,
                'credit' => $total_subtotal,
                'date' => $request->date,
            ]);

            $purchasesData->transaction()->updateOrCreate(
                    ['vo_no' => $request->product_id_list, 'ledger_id' => $request->account_ledger_id],
                    [
                    'date' => $request->date,
                    'ledger_id' => $request->account_ledger_id,
                    'credit' => $total_subtotal
                ]);

            $summary = LedgerSummary::where('ledger_id' ,$request->account_ledger_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if($summary){
                $summary->update(['credit' => $total_subtotal + $summary->credit ]);
            }else{
                LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_ledger_id, 'financial_date' => (new Helper)::activeYear()],
                ['credit' => $total_subtotal]);
            }
            
            // others expanse
            if(!empty($request->expense_ledger_id)){
                $expanse_ledger = AccountLedger::where('id', $request->expense_ledger_id)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $expanse_ledger->id,
                    'account_ledger_id' => $expanse_ledger->account_ledger_id,
                    'account_name' => $expanse_ledger->account_name,
                    'account_ledger__transaction_id' => $request->product_id_list,
                    'debit' => $request->other_expense,
                    'date' => $request->date,
                ]);
    
                $purchasesData->transaction()->updateOrCreate(
                    ['vo_no' => $request->product_id_list, 'ledger_id' => $request->expense_ledger_id],
                    [
                    'date' => $request->date,
                    'ledger_id' => $request->expense_ledger_id,
                    'debit' => $request->other_expense,
                ]);
    
                $Esummary = LedgerSummary::where('ledger_id' ,$request->expense_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())->first();
    
                if($Esummary){
                    $Esummary->update(['debit' => $request->other_expense + $Esummary->debit ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->expense_ledger_id, 'financial_date' => (new Helper)::activeYear()],
                    ['debit' => $request->other_expense]);
                }
            }
            
            
            // send sms
            if($request->has('send_sms') && $request->send_sms == 'yes') {
                $this->send_purchases_sms($purchasesData->id);
            }
            
            (new LogActivity)->addToLog('Purchase Created.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            //dd($ex->getMessage(), $ex->getLine());
            return response()->json($ex->getMessage());
        }
        if($request->print){
            return redirect()->action('MBCorporation\PurchasesController@print_pruchases_invoice',[ $purchasesData->product_id_list] );
        }
        return redirect()->to('/purchases_addlist_list');
    }



    public function edit_purchases($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $godown = Godown::get();
        $accountLedger = AccountLedger::get(['id', 'account_name']);
        $saleMan = SaleMen::get(['id', 'salesman_name']);
        $items = Item::get(['id', 'name']);

        $purchasesAddList  = PurchasesAddList::where('id', $id)->first();
        return view('MBCorporationHome.transaction.purchases_addlist.edit_purchases_addlist',
        compact('godown', 'accountLedger', 'saleMan',
        'items', 'purchasesAddList'));
    }

    public function UpdatePurchasesAddList(Request $request,Helper $helper,  $id)
    {
       
        $request->validate([
            'product_id_list' => 'required',
            'godown_id' => 'required',
            'SaleMan_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
           
            DB::beginTransaction();
            
            StockHistory::where('stockable_type', 'App\PurchasesAddList')
            ->where('stockable_id', $id)
            ->Update(['date' => $request->date]);
            
            $this->deleteDemoProductAddOnVoucher($request,  $id, 'App\PurchasesAddList');
          
            $purchasesAddList = PurchasesAddList::where("id", $id)->first();
            if($request->new_item_id){
                $this->addOnDemoProductUpdateStore($request, $purchasesAddList);
            }

            $for_name = DemoProductAddOnVoucher::where('product_id_list', $purchasesAddList->product_id_list)->get();
            // dd($for_name);

            $total_subtotal = 0;
            $total_subtotal =  ($for_name->sum('subtotal_on_product') + $request->other_expense) - $request->discount_total;
            
            AccountLedgerTransaction::where('account_ledger__transaction_id', $purchasesAddList->product_id_list)->
            where('ledger_id',$purchasesAddList->account_ledger_id )->Update([
                'credit' => $total_subtotal,
                'debit' => 0,
                'date' => $request->date,
            ]);

            Transaction::updateOrCreate(
                ['vo_no' => $request->product_id_list, 'transactionable_id' => $purchasesAddList->id],
                [
                'date' => $request->date,
                'ledger_id' => $request->account_ledger_id,
                'credit' => $total_subtotal
            ]);

            if($purchasesAddList->grand_total !=  $total_subtotal){
                $summary = LedgerSummary::where('ledger_id' ,$request->account_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' => abs($total_subtotal + $summary->credit - $purchasesAddList->grand_total) ]);

                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_ledger_id,
                        'financial_date' => (new Helper)::activeYear()],[ 'credit' => $total_subtotal]);
                }
            }
            
            // if expense ledger is none then delete all ledger
            if($request->expense_ledger_id == 0) {
                $accountLedgerTransaction = AccountLedgerTransaction::where('account_ledger__transaction_id', $purchasesAddList->product_id_list)
                                            ->where('ledger_id',$purchasesAddList->expense_ledger_id )->first();
                
                // other bill reduce from ledger summary
                $summary = LedgerSummary::where('ledger_id' ,$purchasesAddList->expense_ledger_id)
                                        ->where('financial_date', (new Helper)::activeYear())
                                        ->first();
                if($summary){
                LedgerSummary::where('ledger_id' ,$purchasesAddList->expense_ledger_id)
                            ->where('financial_date', (new Helper)::activeYear())
                            ->update([
                                'debit' => abs(($summary->debit ?? 0) - ($purchasesAddList->other_bill ?? 0)),
                            ]);
                }
                
                
                // transaction delete
                Transaction::where([
                    'vo_no' => $request->product_id_list, 
                    'transactionable_id' => $purchasesAddList->id, 
                    'ledger_id' => $purchasesAddList->expense_ledger_id,
                ])->delete();
                
                if($accountLedgerTransaction) {
                    // account ledger transacion delete
                    AccountLedgerTransaction::find($accountLedgerTransaction->id)->delete();
                }
                
            }
        
            // expanse ledger
            if($request->expense_ledger_id != 0){
                // request expense ledger id and purchase add list expense ledger id is same
                if($request->expense_ledger_id == $purchasesAddList->expense_ledger_id){
                    $expanse_ledger = AccountLedger::where('id', $request->expense_ledger_id)->first();
                    AccountLedgerTransaction::updateOrCreate(
                        [
                            'account_ledger__transaction_id' => $purchasesAddList->product_id_list,
                            'ledger_id' => $purchasesAddList->expense_ledger_id,
                        ],
                        [
                            'account_name' => $expanse_ledger->account_name,
                            'credit' => 0,
                            'debit' => $request->other_expense,
                            'date' => $request->date,
                        ]
                    );
        
                    Transaction::updateOrCreate(
                        ['vo_no' => $request->product_id_list, 'transactionable_id' => $purchasesAddList->id, 'ledger_id' => $request->expense_ledger_id],
                        [
                        'date' => $request->date,
                        'ledger_id' => $request->expense_ledger_id,
                        'debit' => $request->other_expense
                    ]);
        
                    if($purchasesAddList->other_bill !=  $request->other_expense){
                        $summary = LedgerSummary::where('ledger_id' ,$request->expense_ledger_id)
                        ->where('financial_date', (new Helper)::activeYear())
                        ->first();
                        if($summary){
                            $summary->update(['debit' => abs($request->other_expense + $summary->debit - $purchasesAddList->other_bill) ]);
        
                        }else{
                            LedgerSummary::updateOrCreate(['ledger_id' =>$request->expense_ledger_id,
                                'financial_date' => (new Helper)::activeYear()],[ 'debit' => $request->other_expense]);
                        }
                    }
                    
                }else if($request->expense_ledger_id != $purchasesAddList->expense_ledger_id) {
                    
                    //********* DEl pre ledger | if ledger is change then delete previouse ledger and set new ledger
                    $accountLedgerTransaction = AccountLedgerTransaction::where('account_ledger__transaction_id', $purchasesAddList->product_id_list)
                                            ->where('ledger_id', $purchasesAddList->expense_ledger_id )->first();
                    
                    // other bill reduce from ledger summary
                    $summary = LedgerSummary::where('ledger_id' ,$purchasesAddList->expense_ledger_id)
                                            ->where('financial_date', (new Helper)::activeYear())
                                            ->first();
                    if($summary){
                    LedgerSummary::where('ledger_id' ,$purchasesAddList->expense_ledger_id)
                                ->where('financial_date', (new Helper)::activeYear())
                                ->update([
                                    'debit' => abs(($summary->debit ?? 0) - ($purchasesAddList->other_bill ?? 0)),
                                ]);
                    }
                    
                    // transaction delete
                    Transaction::where([
                        'vo_no' => $request->product_id_list, 
                        'transactionable_id' => $purchasesAddList->id, 
                        'ledger_id' => $purchasesAddList->expense_ledger_id,
                    ])->delete();
                    
                    if($accountLedgerTransaction)
                        AccountLedgerTransaction::where('id', $accountLedgerTransaction->id)->delete(); // account ledger transacion delete
                        
                        //*************** CREATE New Expense Ledger ********
                        $expanse_ledger = AccountLedger::where('id', $request->expense_ledger_id)->first();
                        AccountLedgerTransaction::create([
                            'account_ledger__transaction_id' => $purchasesAddList->product_id_list,
                            'ledger_id' => $request->expense_ledger_id,
                            'account_ledger_id' => $expanse_ledger->account_ledger_id,
                            'account_name' => $expanse_ledger->account_name,
                            'credit' => 0,
                            'debit' => $request->other_expense,
                            'date' => $request->date,
                        ]);
            
                        Transaction::create([
                            'vo_no' => $request->product_id_list, 
                            'transactionable_id' => $purchasesAddList->id, 
                            'ledger_id' => $request->expense_ledger_id,
                            'date' => $request->date,
                            'ledger_id' => $request->expense_ledger_id,
                            'debit' => $request->other_expense,
                        ]);
            
                        $summary = LedgerSummary::where('ledger_id' ,$request->expense_ledger_id)
                                    ->where('financial_date', (new Helper)::activeYear())->first();
                        if($summary){
                            $summary->update([
                                'debit' => abs($request->other_expense + $summary->debit - $purchasesAddList->other_bill)
                            ]);
                        }else{
                            LedgerSummary::create([
                                'ledger_id' =>$request->expense_ledger_id,
                                'financial_date' => (new Helper)::activeYear()],[ 'debit' => $request->other_expense
                            ]);
                        }
                }
            }
            
             // purchase table update
            $purchasesAddList->update([
                'date' => $request->date,
                'godown_id' => $request->godown_id,
                'sale_name_id' => $request->SaleMan_name,
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
            
           

            (new LogActivity)->addToLog('Purchase Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }
        if($request->print){
            return redirect()->action('MBCorporation\PurchasesController@print_pruchases_invoice',[ $purchasesAddList->product_id_list] );
        }
        return redirect()->to('purchases_addlist_list');
    }

    public function addOnDemoProductUpdateStore($request, $purchasesData)
    {

        foreach ($request->new_item_id as $key=>$for_item_row) {
            // dd(32423);
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

            // purchasesDetails
            $purchasesData->detailsProduct()->create([
                'item_id' => $data_add->item_id,
                'qty' =>    $data_add->qty,
            ]);

            // stock
            $old_Stock = StockDetail::where('item_id', $data_add->item_id)->where('godown_id', $purchasesData->godown_id)->first();

            if ($old_Stock) {
                $new_stock_qty = $old_Stock->qty + $data_add->qty;
                $old_Stock->update([
                    'qty' => $new_stock_qty,
                    'purchases_price' => $data_add->price,
                ]);
            } else {
                $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                $stock['item_id'] = $data_add->item_id;
                $stock['godown_id'] = $purchasesData->godown_id;
                $stock['qty'] = $data_add->qty;
                $stock['purchases_price'] =  $data_add->price;
                StockDetail::create($stock);
            }

             // StockHistory
            $stockHistory['item_id'] = $data_add->item_id;
            $stockHistory['in_qty'] = $data_add->qty;
            $stockHistory['godown_id'] = $purchasesData->godown_id;
            $stockHistory['category_id'] = $data_add->item->category->id;
            $stockHistory['average_price'] = $data_add->price ;
            $purchasesData->stock()->create($stockHistory);

            // ItemCount
            $itemCount = ItemCount::where('item_id', $data_add->item_id)->first();
            if ($itemCount) {
                $itemCount->update(['purchase_qty' => $data_add->qty + $itemCount->purchase_qty]);
            } else {
                ItemCount::updateOrCreate(['item_id' => $data_add->item_id], ['purchase_qty' =>$data_add->qty ]);
            }

        }
    }
    public function delete_purchases($product_id_list)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            $for_name = DemoProductAddOnVoucher::where('product_id_list', $product_id_list)->get();
            foreach ($for_name as $for_name_row) {
                $PurchasesAddList = PurchasesAddList::where('product_id_list', $for_name_row->product_id_list)->get();
                foreach ($PurchasesAddList as $purchases_row) {
                    $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->get();
                    foreach ($old_Stock_qty as $old_Stock_qty_row) {
                        $new_stock_qty = $old_Stock_qty_row->qty - $for_name_row->qty;
                        StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->update([
                            'qty' => $new_stock_qty,
                        ]);
                    };

                    Transaction::where('vo_no', $purchases_row->product_id_list)->where('transactionable_id', $purchases_row->id)
                    ->delete();


                    $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['purchase_qty' =>  $itemCount['purchase_qty']- $for_name_row['qty'] ]);
                    }
                    PurchaseDetails::where('purchaseable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                    ->where('purchaseable_type', 'App\PurchasesAddList')->delete();
                    $for_name_row->delete();
                }

            }
            AccountLedgerTransaction::where('account_ledger__transaction_id', $product_id_list)->delete();
            $pur= PurchasesAddList::where('product_id_list', $product_id_list)->with('stock')->first();
            $summary = LedgerSummary::where('ledger_id' ,$pur->account_ledger_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if($summary){
                $summary->update(['credit' =>  abs($summary->credit - $purchases_row->grand_total) ]);
            }

            // other expanse

            $Esummary = LedgerSummary::where('ledger_id' ,$pur->expense_ledger_id)
            ->where('financial_date', (new Helper)::activeYear())->first();
            if($Esummary){
                $Esummary->update(['debit' =>  abs($Esummary->debit - $purchases_row->other_bill) ]);
            }

            $pur->stock()->delete();
            $pur->delete();
            (new LogActivity)->addToLog('Purchase Deleted.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
            // return redirect()->to('purchases_addlist_list')->with('mes', 'this data used another table');
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->to('purchases_addlist_list')->with('mes', 'this data used another table');

    }

    public function send_purchases_sms($id)
    {
        try {
        $person =  PurchasesAddList::whereId($id)->with('ledger')->first();
        $closingBalance = LedgerSummary::where('id', $person->account_ledger_id)
        ->where('financial_date', (new Helper)::activeYear())

        ->first()->grand_total;
        if($closingBalance> 1){
            $closingHtml='Closing Bal:'.$closingBalance.'(Dr)';
        }else{
            $closingHtml='Closing Bal:'.$closingBalance.'(Cr)';
        }
        if(optional($person->ledger)->account_ledger_phone){
            $mobile = optional($person->ledger)->account_ledger_phone;
            $text =  '. Pur-no :'.$person->product_id_list.', date'. date('m-d-Y', strtotime($person->date)).', Total Amount: '.$person->grand_total.', '.$closingHtml;
            SMS::sendSMS($mobile, $text);
        }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }
    public function send_purchases_return_sms($id)
    {
        try {
        $person =  PurchasesReturnAddList::whereId($id)->with('ledger')->first();
        $closingBalance = LedgerSummary::where('id', $person->account_ledger_id)
        ->where('financial_date', (new Helper)::activeYear())
        ->first()->grand_total;
        if($closingBalance> 1){
            $closingHtml='Closing Bal:'.$closingBalance.'(Dr)';
        }else{
            $closingHtml='Closing Bal:'.$closingBalance.'(Cr)';
        }
        if(optional($person->ledger)->account_ledger_phone){
            $mobile = optional($person->ledger)->account_ledger_phone;
            $text =  '. PurReturn-no :'.$person->product_id_list.', date'. date('m-d-Y', strtotime($person->date)).', Total Amount: '.$person->grand_total.', '.$closingHtml;
            SMS::sendSMS($mobile, $text);
        }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }
    public function view_purchases($id)
    {
        $purchasesAddList = PurchasesAddList::whereId($id)->with('demoProducts')->first();
        return view('MBCorporationHome.transaction.purchases_addlist.view_purchases', compact('purchasesAddList'));
    }

    public function print_pruchases_invoice($product_id_list)
    {
        $purchase = PurchasesAddList::where('product_id_list', $product_id_list)->first();
        return view('MBCorporationHome.transaction.purchases_addlist.print_pruchases_invoice', compact('product_id_list', 'purchase'));
    }



    //..............................Purchases and Sales Demo Product And funcation Start ............................................
    //..............................Purchases and Sales Demo Product And funcation Start ............................................
    //..............................Purchases and Sales Demo Product And funcation Start ............................................
    public function product_as_price($id)
    {
        $data = Item::where('id', $id)->get();
        return response()->json($data);
    }

    public function account_details_for_invoice($account_ledger_id)
    {
        $data = AccountLedger::where('id', $account_ledger_id)->get();
        return response()->json($data);
    }

    public function addOnDemoProductStore($request)
    {
        foreach ($request->new_item_id?? $request->item_id as $key => $item) {
            $id_row = rand(111111, 999999).'-'.date('y');
            $data_add = DemoProductAddOnVoucher::create([
                'id_row' => $id_row,
                'product_id_list' => $request->product_id_list,
                'page_name' => $request->page_name,
                'item_id' => $item,
                'price' =>$request->new_price[$key]?? $request->price[$key],
                'discount' => $request->new_discount[$key]?? $request->discount[$key],
                'qty' => $request->new_qty[$key]??$request->qty[$key],
                'date' => $request->date,
                'subtotal_on_product' =>  $request->new_subtotal[$key] ?? $request->subtotal[$key],
            ]);
        }

        return response()->json($data_add);
    }
    public function product_new_fild($product_id_list)
    {
        $data = DemoProductAddOnVoucher::where('product_id_list', $product_id_list)->with('item')->get();
        return response()->json($data);
    }
    public function product_delete_fild($id_row, $stock = null)
    {
        $data = DemoProductAddOnVoucher::where('id_row', $id_row)->delete();
        return response()->json($data);
    }
    public function account_pre_amount($account_id_for_preamound)
    {
        $data = AccountLedgerTransaction::where('account_ledger_id', $account_id_for_preamound)->get();

        return response()->json($data);
    }

    public function deleteDemoProductAddOnVoucher($request, $id,$type )
    {
        $productsId =[];
        try {
            if($type === 'App\PurchasesAddList'){

                $purchases_row = PurchasesAddList::where('product_id_list',  $request->product_id_list)->with('stock')->first();
            }else{
                $purchases_row = PurchasesReturnAddList::where('product_id_list',  $request->product_id_list)->with('stock')->first();

            }
            $demoProducts = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
            $allProducts = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get(['item_id'])->toArray();
            if($request->item_id){
                foreach ($allProducts as $key => $value) {
                    array_push($productsId, $value['item_id']);
                }
                $deletedProducts = array_diff($productsId, $request->item_id);

                if(count($deletedProducts) > 0 && $deletedProducts != null){
                    foreach ($deletedProducts as $key => $value) {

                        $oldQty = DemoProductAddOnVoucher::where('product_id_list', $purchases_row->product_id_list)->where('item_id', $value)->first();
                        PurchaseDetails::where('purchaseable_id', $id)->where('item_id',  $value)->where('purchaseable_type', $type)->delete();
                        $old_Stock = StockDetail::where('item_id',  $value)->where('godown_id', $purchases_row->godown_id)->first();

                        $itemCount = ItemCount::where('item_id', $value)->first();

                        if($type === 'App\PurchasesAddList'){

                            $StockHistory = StockHistory::where([
                                ['item_id' , $value],['godown_id', $purchases_row->godown_id],['stockable_type',  $type],['stockable_id' , $id],
                                ['in_qty',  $oldQty->qty]
                            ])->first();
                            if( $StockHistory->in_qty < $oldQty->qty){
                                $old_Stock->update(['qty' =>$old_Stock->qty + $oldQty->qty ]);
                            }
                            $StockHistory->delete();
                            if( $itemCount['purchase_qty'] >= 0){
                                $itemCount->update(['purchase_qty' => $itemCount['purchase_qty']  - $oldQty['qty']]);
                            }else{
                                $itemCount->update(['purchase_qty' => $itemCount['purchase_qty']  + $oldQty['qty']]);

                            }
                        }else{
                            $StockHistory = StockHistory::where([
                                ['item_id' , $value],['godown_id', $purchases_row->godown_id],['stockable_type',  $type],['stockable_id' , $id],
                                ['out_qty',  $oldQty->qty]
                            ])->first();
                            if( $StockHistory->out_qty < $oldQty->qty){
                                $old_Stock->update(['qty' =>$old_Stock->qty - $oldQty->qty ]);
                            }
                            $StockHistory->delete();
                            if($itemCount['purchase_return_qty'] >= 0){
                                $itemCount->update(['purchase_return_qty' => $itemCount['purchase_return_qty']  - $oldQty['qty']]);
                            }else{
                                $itemCount->update(['purchase_return_qty' => $itemCount['purchase_return_qty']  + $oldQty['qty']]);
                            }
                        }

                        PurchaseDetails::where('purchaseable_id', $purchases_row->id)->where('item_id',  $value)->where('qty', $oldQty->qty)
                        ->where('purchaseable_type', $type)->delete();

                        $oldQty->delete();
                    }
                }
            }else{
                if($type == "App\PurchasesAddList"){


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
                            if ($itemCount['purchase_qty'] >= 0) {
                                $itemCount->update(['purchase_qty' =>  $itemCount['purchase_qty'] - $for_name_row['qty'] ]);
                            }else{
                                $itemCount->update(['purchase_qty' =>  $itemCount['purchase_qty'] + $for_name_row['qty'] ]);

                            }

                            PurchaseDetails::where('purchaseable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                            ->where('qty',  $for_name_row->qty)
                            ->where('purchaseable_type', $type)->delete();
                            $for_name_row->delete();

                    }

                }else{
                    // $purchases_row = PurchasesReturnAddList::where('product_id_list', $request->product_id_list)->first();
                    foreach ($demoProducts as $for_name_row) {

                            $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->get();

                            $StockHistory =StockHistory::where([
                                ['item_id' , $for_name_row->item_id],['godown_id', $purchases_row->godown_id],['stockable_type',  $type],['stockable_id' , $id],
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

                            if ( $itemCount['purchase_return_qty'] >= 0) {
                                $itemCount->update(['purchase_return_qty' =>  $itemCount['purchase_return_qty'] - $for_name_row['qty'] ]);
                            }else{
                                $itemCount->update(['purchase_return_qty' =>  $itemCount['purchase_return_qty'] + $for_name_row['qty'] ]);
                            }

                            PurchaseDetails::where('purchaseable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                            ->where('qty',  $for_name_row->qty)->where('purchaseable_type', $type)->delete();
                            $for_name_row->delete();

                    }


                }

            }

        } catch (\Exception $ex) {
            dd($ex->getMessage(), $ex->getLine());
        }
        return true;
    }
    //..............................Purchases and Sales Demo Product And funcation End ............................................



    //..............................add purchases_order ............................................
    //..............................add purchases_order ............................................


    //add purchases_return .....................
    //add purchases_return .....................
    //add purchases_return .....................
    //add purchases_return .....................
    public function purchases_return_datatable()
    {
        
        $purchase_return_add_list = PurchasesReturnAddList::with(['ledger', 'demoProducts'])
        ->orderBy('date', 'desc');
        return DataTables::eloquent($purchase_return_add_list)
        ->addIndexColumn()
        ->editColumn('date', function(PurchasesReturnAddList $purchase_return_add_list) {
            return date('d-m-Y', strtotime($purchase_return_add_list->date));
        })
        ->addColumn('ledger_name', function(PurchasesReturnAddList $purchase_return_add_list) {
            return $purchase_return_add_list->ledger->account_name ?? "";
        })
        ->addColumn('item_details', function(PurchasesReturnAddList $purchase_return_add_list) {
            return $purchase_return_add_list->demoProducts->pluck('item.name')->implode('<br/>');
        })
        ->addColumn('qty', function(PurchasesReturnAddList $purchase_return_add_list) {
            return $purchase_return_add_list->demoProducts->pluck('qty')->implode('<br/>');
        })
        ->addColumn('price', function(PurchasesReturnAddList $purchase_return_add_list) {
            return $purchase_return_add_list->demoProducts->map(function($demo_product) {
                return new_number_format($demo_product->price ?? 0);
            })->implode('<br/>');
        })
        ->addColumn('total_price', function(PurchasesReturnAddList $purchase_return_add_list) {
            return $purchase_return_add_list->demoProducts->map(function($demo_product) {
                return new_number_format(($demo_product->price ?? 0) * ($demo_product->qty ?? 0));
            })->implode('<br/>');
        })
        ->addColumn('action', function(PurchasesReturnAddList $purchase_return_add_list) {
            return make_action_btn([
                '<a href="'.route("edit_purchases_return", ['product_id_list' => $purchase_return_add_list->id]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_purchases_return",['product_id_list' => $purchase_return_add_list->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="'.route("send_purchases_return_sms",['product_id_list' => $purchase_return_add_list->id]).'" onclick="alert("'.'"Do You want to send sms?"'.'")" class="dropdown-item"><i class="far fa-envelope"></i> Send SMS</a>',
                '<a href="javascript:void(0)" data-id="'.$purchase_return_add_list->product_id_list.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("print_pruchases_return_invoice", ['product_id_list' => $purchase_return_add_list->product_id_list]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['item_details', 'qty', 'price', 'total_price', 'action'])
        ->make(true);
    }
    public function purchases_return_addlist(Request $request)
    {
        if($request->ajax()) {
            return $this->purchases_return_datatable();
        }
        return view('MBCorporationHome.transaction.purchases_return_addlist.index');
    }
    public function purchases_return_addlist_form()
    {
        $Godwn = Godown::get();
        $account = AccountLedger::get();
        $SaleMan = SaleMen::get();
        $Item = Item::get();
        return view('MBCorporationHome.transaction.purchases_return_addlist.purchases_return_addlist_form', compact('Godwn', 'account', 'SaleMan', 'Item'));
    }
    public function print_pruchases_return_invoice($product_id_list)
    {
        $purchase_return = PurchasesReturnAddList::where('product_id_list', $product_id_list)->first();
        return view('MBCorporationHome.transaction.purchases_return_addlist.print_report', compact('product_id_list', 'purchase_return'));
    }

    public function SaveAllData_return_store(Request $request, Helper $helper)
    {
        $request->validate([
            'product_id_list' => 'required|unique:purchases_return_add_lists',
            'godown_id' => 'required',
            'SaleMan_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            $total_subtotal = 0;
            $this->addOnDemoProductStore($request);
            $for_name = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get();
            $total_subtotal =  ($for_name->sum('subtotal_on_product') + $request->other_bill) - $request->discount_total;

            $purchasesData= PurchasesReturnAddList::create([
                'date'                  => $request->date,
                'product_id_list'       => $request->product_id_list,
                'godown_id'             => $request->godown_id,
                'sale_man_id'          => $request->SaleMan_name,
                'account_ledger_id'     => $request->account_ledger_id,
                'order_no'              => $request->order_no,
                'other_bill'            => $request->other_bill,
                'discount_total'        => $request->discount_total,
                'pre_amount'            => $request->pre_amount,
                'shipping_details'      => $request->shipping_details,
                'delivered_to_details'  => $request->delivered_to_details,
                'grand_total'           => $total_subtotal
            ]);
            foreach ($for_name as $for_name_row) {

                // purchasesDetails
                $purchasesData->detailsProduct()->create([
                    'item_id' => $for_name_row->item_id,
                    'qty' =>    $for_name_row->qty,
                ]);

                // stock
                $old_Stock = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $request->godown_id)->first(); // dd($for_name_row,$old_Stock_qty);
                if ($old_Stock) {
                    $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'purchases_price' => $for_name_row->price,
                    ]);
                } else {
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $request->godown_id;
                    $stock['qty'] = (-1*$for_name_row->qty);
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }


                 // StockHistory
                $stockHistory['item_id'] = $for_name_row->item_id;
                $stockHistory['out_qty'] = $for_name_row->qty;
                $stockHistory['godown_id'] = $request->godown_id;
                $stockHistory['category_id'] = $for_name_row->item->category->id;
                $stockHistory['average_price'] = $for_name_row->price ;
                $purchasesData->stock()->create($stockHistory);

                // ItemCount
                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                if ($itemCount) {
                    $itemCount->update(['purchase_return_qty' =>  $itemCount->purchase_return_qty + $for_name_row->qty]);
                } else {
                    ItemCount::updateOrCreate(['item_id' => $for_name_row->item_id], ['purchase_return_qty' => $for_name_row->qty]);
                }

            }

            $account_ledger = AccountLedger::where('id', $request->account_ledger_id)->first();

            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->account_ledger_id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->product_id_list,
                'debit' => $total_subtotal,
            ]);

            $purchasesData->transaction()->updateOrCreate(
                ['vo_no' => $request->product_id_list],
                [
                'date' => $request->date,
                'ledger_id' => $request->account_ledger_id,
                'debit' => $total_subtotal
            ]);

            $summary = LedgerSummary::where('ledger_id' ,$request->account_ledger_id)
            ->where('financial_date', (new Helper)::activeYear())->first();
            if($summary){
                $summary->update(['debit' => $summary->debit + $total_subtotal   ]);
            }else{
                LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_ledger_id, 'financial_date' => (new Helper)::activeYear()],[
                        'debit' => $total_subtotal
                    ]);
            }
            (new LogActivity)->addToLog('Purchase Return Created.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json($ex->getMessage(), $ex->getLine());
        }
        if($request->print){
            return redirect()->action('MBCorporation\PurchasesController@print_pruchases_return_invoice',[ $purchasesData->product_id_list] );
        }
        return redirect()->to('purchases_return_addlist');
    }

    public function edit_return_purchases($id)
    {
        $godowns      = Godown::get();
        $accounts    = AccountLedger::get();
        $SaleMan    = SaleMen::get();
        $items       = Item::get();
        $purchasesAddList = PurchasesReturnAddList::where('id', $id)->with('ledger', 'saleMen', 'godown')->first();
        return view('MBCorporationHome.transaction.purchases_return_addlist.edit_purchases_return_addlist', compact('godowns', 'accounts', 'SaleMan', 'items', 'purchasesAddList'));
    }

    public function UpdatePurchasesaReturnddlist(Request $request, Helper $helper, $id)
    {
        $request->validate([
            'product_id_list' => 'required',
            'godown_id' => 'required',
            'SaleMan_name' => 'required',
            'account_ledger_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            $this->deleteDemoProductAddOnVoucher($request,  $id, 'App\PurchasesReturnAddList');
            $purchaseReturnDetails = PurchasesReturnAddList::where('id', $id)->with('ledger', 'saleMen', 'godown')->first();
            if($request->new_item_id){
               $this->addOnDemoProductReturnUpdateStore($request, $purchaseReturnDetails);
            }
            $total_subtotal = 0;
            $for_name = DemoProductAddOnVoucher::where('product_id_list', $purchaseReturnDetails->product_id_list)->get();
            // dd(   $for_name );
            // foreach ($for_name as $for_name_row) {

            //     $purchaseDetails =  PurchaseDetails::where('purchaseable_id', $id)->where('item_id',  $for_name_row->item_id)
            //     ->where('purchaseable_type', 'App\PurchasesReturnAddList')->first();

            //     $old_Stock = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $request->godown_id)->first();

            //     if ($old_Stock->godown_id == $purchaseReturnDetails->godown_id) {
            //         $new_stock_qty = $old_Stock->qty??0 - $for_name_row->qty??0 - $purchaseDetails->qty??0 ;
            //         $old_Stock->update([
            //             'qty' => $new_stock_qty,
            //             'purchases_price' => $for_name_row->price,
            //         ]);

            //     } else {
            //         $new_stock_qtys = $old_Stock->qty - $purchaseDetails->qty ;
            //         $old_Stock->update(['qty' => $new_stock_qtys]);


            //         $stock['st_id']         = "ST" . rand(111111, 999999).'-'.date('y');
            //         $stock['item_id']       = $for_name_row->item_id;
            //         $stock['godown_id']     = $request->godown_id;
            //         $stock['qty']           = (-1*$for_name_row->qty);
            //         $stock['purchases_price'] =  $for_name_row->price;
            //         StockDetail::create($stock);
            //     }

            //     $stockHistory['item_id'] = $for_name_row->item_id;
            //     $stockHistory['in_qty'] = $for_name_row->qty;
            //     $stockHistory['godown_id'] = $request->godown_id;
            //     $stockHistory['category_id'] = $for_name_row->item->category->id;
            //     $stockHistory['average_price'] = $for_name_row->price ;
            //     StockHistory::where([
            //         ['item_id' , $for_name_row->item_id],['godown_id', $old_Stock->godown_id],['stockable_type' , 'App\PurchasesReturnAddList'],['stockable_id' , $purchaseReturnDetails->id ]
            //     ])->update($stockHistory);

            //     $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();

            //     if ($itemCount) {
            //         $itemCount->update(['purchase_return_qty' => $for_name_row['qty']??0 + $itemCount['purchase_return_qty']??0 - $purchaseDetails['qty']??0 ]);
            //     } else {
            //         ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['purchase_return_qty' => $for_name_row->qty ?? 0]);
            //     }

            //     if( $purchaseDetails){
            //         $purchaseDetails->update(['qty' => $for_name_row->qty ]);
            //     }

            // };
            $total_subtotal = $for_name->sum('subtotal_on_product')  + $request->other_bill - $request->discount_total;
            AccountLedgerTransaction::where('account_ledger__transaction_id', $purchaseReturnDetails->product_id_list)->Update([
                'debit' => $total_subtotal,
                'credit' => 0,
            ]);

            Transaction::updateOrCreate(
                ['vo_no' => $request->product_id_list, 'transactionable_id' => $purchaseReturnDetails->id],
                [
                'date' => $request->date,
                'ledger_id' => $purchaseReturnDetails->account_ledger_id,
                'debit' => $total_subtotal
            ]);

            if($purchaseReturnDetails->grand_total !=  $total_subtotal){

                $summary = LedgerSummary::where('ledger_id' ,$request->account_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())->first();

                if($summary){
                    $amount = abs($summary->debit - $purchaseReturnDetails->grand_total) + $total_subtotal;
                    $summary->update(['debit' =>  $amount ]);

                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_ledger_id, 'financial_date' => (new Helper)::activeYear()],
                    ['debit' => $total_subtotal]);
                }

            }

            $purchaseReturnDetails->update([
                'date'                  => $request->date,
                'product_id_list'       => $request->product_id_list,
                'godown_id'             => $request->godown_id,
                'sale_man_id'          => $request->SaleMan_name,
                'account_ledger_id'     => $purchaseReturnDetails->account_ledger_id,
                'order_no'              => $request->order_no,
                'other_bill'            => $request->other_bill,
                'discount_total'        => $request->discount_total,
                'pre_amount'            => $request->pre_amount,
                'shipping_details'      => $request->shipping_details,
                'delivered_to_details'  => $request->delivered_to_details,
                'grand_total'           => $total_subtotal,
            ]);
            (new LogActivity)->addToLog('Purchase Return Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            dd($ex->getMessage(), $ex->getLine() );
            DB::rollBack();
        }
        if($request->print){
            return redirect()->action('MBCorporation\PurchasesController@print_pruchases_return_invoice',[ $purchaseReturnDetails->product_id_list] );
        }
        return redirect()->to('purchases_return_addlist');

    }

    public function addOnDemoProductReturnUpdateStore($request , $purchaseReturnDetails)
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

            // purchasesDetails
            $purchaseReturnDetails->detailsProduct()->create([
                'item_id' => $data_add->item_id,
                'qty' =>    $data_add->qty,
            ]);

            // stock
            $old_Stock = StockDetail::where('item_id', $data_add->item_id)->where('godown_id', $request->godown_id)->first(); // dd($for_name_row,$old_Stock_qty);
            if ($old_Stock) {
                $new_stock_qty = $old_Stock->qty - $data_add->qty;
                $old_Stock->update([
                    'qty' => $new_stock_qty,
                    'purchases_price' => $data_add->price,
                ]);
            } else {
                $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                $stock['item_id'] = $data_add->item_id;
                $stock['godown_id'] = $request->godown_id;
                $stock['qty'] = (-1*$data_add->qty);
                $stock['purchases_price'] =  $data_add->price;
                StockDetail::create($stock);
            }


             // StockHistory
            $stockHistory['item_id'] = $data_add->item_id;
            $stockHistory['out_qty'] = $data_add->qty;
            $stockHistory['godown_id'] = $request->godown_id;
            $stockHistory['category_id'] = $data_add->item->category->id;
            $stockHistory['average_price'] = $data_add->price ;
            $purchaseReturnDetails->stock()->create($stockHistory);

            // ItemCount
            $itemCount = ItemCount::where('item_id', $data_add->item_id)->first();
            if ($itemCount) {
                $itemCount->update(['purchase_return_qty' =>  $itemCount->purchase_return_qty + $data_add->qty]);
            } else {
                ItemCount::updateOrCreate(['item_id' => $data_add->item_id], ['purchase_return_qty' => $data_add->qty]);
            }

        }
    }
    public function delete_return_purchases($product_id_list)
    {

        try {
            DB::beginTransaction();

             $for_name = DemoProductAddOnVoucher::where('product_id_list', $product_id_list)->get();
            foreach ($for_name as $for_name_row) {
                $PurchasesAddList = PurchasesReturnAddList::where('product_id_list', $for_name_row->product_id_list)->get();
                foreach ($PurchasesAddList as $purchases_row) {
                    $old_Stock_qty = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->get();
                    foreach ($old_Stock_qty as $old_Stock_qty_row) {
                        $new_stock_qty = $old_Stock_qty_row->qty - $for_name_row->qty;
                        StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $purchases_row->godown_id)->update([
                            'qty' => $new_stock_qty,
                        ]);
                    };
                    Transaction::where('vo_no', $purchases_row->product_id_list)
                    ->where('transactionable_type', 'App\PurchasesReturnAddList')->where('transactionable_id', $purchases_row->id)
                    ->delete();

                    $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();

                    if ( $itemCount) {
                        $itemCount->update(['purchase_return_qty' =>  $itemCount['purchase_return_qty'] - $for_name_row['qty'] ]);
                    }else{
                        ItemCount::updateOrCreate(['item_id' => $for_name_row->item_id], ['purchase_return_qty' => $for_name_row->qty]);
                    }

                    PurchaseDetails::where('purchaseable_id', $purchases_row->id)->where('item_id',  $for_name_row->item_id)
                    ->where('purchaseable_type', 'App\PurchasesReturnAddList')->delete();
                    $for_name_row->delete();
                }

            };

            AccountLedgerTransaction::where('account_ledger__transaction_id', $product_id_list)->delete();
            $pur= PurchasesReturnAddList::where('product_id_list', $product_id_list)->with('stock')->first();
            $summary = LedgerSummary::where('ledger_id',$pur->account_ledger_id)->where('financial_date', (new Helper)::activeYear())->first();
            
            if($summary){
                $summary->update(['debit' =>  abs($summary->debit - $pur->grand_total) ]);
            }
            $pur->stock()->delete();
            $pur->delete();
            (new LogActivity)->addToLog('Purchase Return Deleted.');


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
        //return redirect()->to('purchases_return_addlist');
    }


}
