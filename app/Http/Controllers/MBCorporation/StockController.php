<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Godown;
use App\Item;
use App\StockTransfer;
use App\DemoProductAddOnVoucher;
use App\StockDetail;
use App\StockAdjustment;
use App\Demostockadjusment;
use Session;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Helpers\Product;
use App\ItemCount;
use App\StockHistory;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;
use Datatables;

class StockController extends Controller
{
    public function stock_transfer_addlist()
    {
        $stockTransfer = StockTransfer::with(['locationForm', 'locationTo'])->paginate(10);
        return view('MBCorporationHome.transaction.stock_transfer.index', compact('stockTransfer'));
    }

    public function stock_transfer_addlist_form()
    {
    	$godown = Godown::get();
		$items = Item::get();
        return view('MBCorporationHome.transaction.stock_transfer.stock_transfer_addlist_form',compact('items','godown'));
    }

    public function SaveAllData_StockTransfer_store(Request $request, Helper $helper)
    {

        $request->validate([
            'product_id_list' => 'required|unique:stock_transfers',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,

        ]);
        try {
            DB::beginTransaction();
            $data_add =StockTransfer::create([
                'product_id_list'=>$request->product_id_list,
                'reference_txt'=>$request->reference_txt,
                'location_form'=>$request->location_form,
                'location_to'=>$request->location_to,
            ]);
            $this->addOnDemoProductStore($request);
            $for_name = DemoProductAddOnVoucher::where('product_id_list',$request->product_id_list)->get();
            foreach ($for_name as $for_name_row) {
                $old_Stock  = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$request->location_form)->first();
                if( $old_Stock){
                    $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                    ]);
                }else{
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $request->location_form;
                    $stock['qty'] = $for_name_row->qty;
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }

                $old_p_Stock_qty = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$request->location_to)->first();
                if($old_p_Stock_qty){
                    $new_p_stock_qty = $old_p_Stock_qty->qty + $for_name_row->qty;
                    $old_p_Stock_qty->update([
                        'qty' => $new_p_stock_qty,
                    ]);
                }else{
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $request->location_to;
                    $stock['qty'] = $for_name_row->qty;
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }
                  // StockHistory
                if($request->location_form){
                    $stockOutHistory['item_id'] = $for_name_row->item_id;
                    $stockOutHistory['out_qty'] = $for_name_row->qty;
                    $stockOutHistory['godown_id'] = $request->location_form;
                    $stockOutHistory['category_id'] = $for_name_row->item->category->id;
                    $stockOutHistory['average_price'] = $for_name_row->price ;
                    $data_add->stock()->create($stockOutHistory);

                }
                if($request->location_to){
                    $stockHistory['item_id'] = $for_name_row->item_id;
                    $stockHistory['in_qty'] = $for_name_row->qty;
                    $stockHistory['godown_id'] = $request->location_to;
                    $stockHistory['category_id'] = $for_name_row->item->category->id;
                    $stockHistory['average_price'] = $for_name_row->price ;
                    $data_add->stock()->create($stockHistory);
                }

            };



            (new LogActivity)->addToLog('StockTransfer Created.');


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return redirect()->to('stock_transfer_addlist');

    }
    public function addOnDemoProductStore($request)
    {
        foreach ($request->new_item_id?? $request->item_id as $key => $item) {
            $id_row = rand(111111, 999999);
            $data_add = DemoProductAddOnVoucher::create([
                'id_row' => $id_row,
                'product_id_list' => $request->product_id_list,
                'page_name' => $request->page_name,
                'item_id' => $item,
                'price' =>$request->new_price[$key]?? $request->price[$key],
                'qty' => $request->new_qty[$key]??$request->qty[$key],
                'date' => $request->date,
                'subtotal_on_product' =>  $request->new_subtotal[$key] ?? $request->subtotal[$key],
            ]);

        }

        return response()->json($data_add);
    }
    public function edit_StockTransfer($product_id_list)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $godown = Godown::get();
        $Item = Item::get();
        $stockTransfer = StockTransfer::where('id',$product_id_list)->with(['locationForm', 'locationTo'])->first();
        return view('MBCorporationHome.transaction.stock_transfer.edit_stock_transfer',compact('Item','godown','stockTransfer'));
    }

    public function Update_StockTransfer(Request $request,Helper $helper, $id)
    {
        $request->validate([
            'product_id_list' => 'required|unique:stock_transfers,product_id_list,' .$id,
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,

        ]);

        try {
            DB::beginTransaction();
                $this->deleteDemoProductAddOnVoucher($request,  $id);

            $data_add = StockTransfer::where('id',$id)->first();
            if($request->new_item_id){
                $this->addOnUpdateDemoProductStore($request,  $data_add);
            }
            $for_name = DemoProductAddOnVoucher::where('product_id_list',$request->product_id_list)->get();
            if($request->location_form  !=  $data_add->location_form){

                foreach ($for_name as $for_name_row) {
                    // old stock delete
                    $changeable_stock_qty=StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id', $data_add->location_form)->first();
                    $new_stock_qty = $changeable_stock_qty->qty + $for_name_row->qty;
                    $changeable_stock_qty->update([
                        'qty' => $new_stock_qty,
                    ]);
                    StockHistory::where([
                        ['item_id' , $for_name_row->item_id],['godown_id', $data_add->location_form],['stockable_type' , 'App\StockTransfer'],['stockable_id' , $data_add->id ]
                    ])->where('out_qty', '>', 0)->delete();

                    //new stock store
                    $old_Stock_qty=StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id', $request->location_form)->first();
                    if( $old_Stock_qty){
                            $new_stock_qty = $old_Stock_qty->qty - $for_name_row->qty;
                            StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$request->location_form)->update([
                                'qty' => $new_stock_qty,
                            ]);
                    }else{
                        $stock['st_id']     = "ST" . rand(111111, 999999).'-'.date('y');
                        $stock['item_id']   = $for_name_row->item_id;
                        $stock['godown_id'] = $request->location_form;
                        $stock['qty']       = $for_name_row->qty;
                        $stock['purchases_price'] =  $for_name_row->price;
                        StockDetail::create($stock);
                    }

                    $stockOutHistory['item_id'] = $for_name_row->item_id;
                    $stockOutHistory['out_qty'] = $for_name_row->qty;
                    $stockOutHistory['godown_id'] = $request->location_form;
                    $stockOutHistory['category_id'] = $for_name_row->item->category->id;
                    $stockOutHistory['average_price'] = $for_name_row->price ;
                    $data_add->stock()->create($stockOutHistory);
                }
            }
            if($request->location_to  !=  $data_add->location_to){
                foreach ($for_name as $for_name_row) {
                    // old stock delete
                    $stock_qty=StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id', $data_add->location_to)->first();
                    $new_stock_qty = $stock_qty->qty - $for_name_row->qty;
                    $stock_qty->update([
                        'qty' => $new_stock_qty,
                    ]);
                    StockHistory::where([
                        ['item_id' , $for_name_row->item_id],['godown_id', $data_add->location_to],['stockable_type' , 'App\StockTransfer'],['stockable_id' , $data_add->id ]
                    ])->where('in_qty', '>', 0)->delete();

                    //new stock store
                    $old_Stock_qty=StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id', $request->location_to)->first();
                    if( $old_Stock_qty){
                            $new_stock_qty = $old_Stock_qty->qty + $for_name_row->qty;
                            StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$request->location_form)->update([
                                'qty' => $new_stock_qty,
                            ]);
                    }else{
                        $stock['st_id']     = "ST" . rand(111111, 999999).'-'.date('y');
                        $stock['item_id']   = $for_name_row->item_id;
                        $stock['godown_id'] = $request->location_form;
                        $stock['qty']       = $for_name_row->qty;
                        $stock['purchases_price'] =  $for_name_row->price;
                        StockDetail::create($stock);
                    }

                    $stockOutHistory['item_id'] = $for_name_row->item_id;
                    $stockOutHistory['in_qty'] = $for_name_row->qty;
                    $stockOutHistory['godown_id'] = $request->location_form;
                    $stockOutHistory['category_id'] = $for_name_row->item->category->id;
                    $stockOutHistory['average_price'] = $for_name_row->price ;
                    $data_add->stock()->create($stockOutHistory);
                }
            }


            $data_add->update([
                'date'=>$request->date,
                'reference_txt'=>$request->reference_txt,
                'location_form'=>$request->location_form,
                'location_to'=>$request->location_to,
            ]);
            (new LogActivity)->addToLog('StockTransfer Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            //dd($ex->getMessage());
        }

         return redirect()->to('stock_transfer_addlist');

    }
    
    
    public function stocktransfer_product_delete_fild_from_add($id_row)
    {

        // new add location_form
        $info = DemoProductAddOnVoucher::where('id_row',$id_row)->first();
        $StockTransfer =  StockTransfer::where('product_id_list',$info->product_id_list)->first();

        $old_Stock  = StockDetail::where('item_id',$info->item_id )->where('godown_id',$StockTransfer->location_form)->first();
        $new_stock_qty = abs($old_Stock->qty) + $info->qty;
        $old_Stock->update([
            'qty' => $new_stock_qty,
        ]);

        $old_p_Stock_qty = StockDetail::where('item_id',$info->item_id )->where('godown_id',$StockTransfer->location_to)->first();
        if($old_p_Stock_qty){
            $new_p_stock_qty = abs($old_p_Stock_qty->qty) - $info->qty;
            $old_p_Stock_qty->update([
                'qty' => $new_p_stock_qty,
            ]);
        }

        $info->delete();
        StockHistory::where([
            ['stockable_id' , $StockTransfer->id ],
            ['stockable_type' , 'App\StockTransfer'],
            ['item_id' , $info->item_id],
        ])->delete();
        // new add end
        $data = DemoProductAddOnVoucher::where('id_row',$id_row)->delete();
        return redirect()->back();
    }

    public function addOnUpdateDemoProductStore($request, $data_add)
    {

        foreach ($request->new_item_id as $key => $item) {
            $id_row = rand(111111, 999999).'-'.date('y');
            $for_name_row = DemoProductAddOnVoucher::create([
                'id_row' => $id_row,
                'product_id_list' => $request->product_id_list,
                'page_name' => $request->page_name,
                'item_id' => $item,
                'price' =>$request->new_price[$key]?? $request->price[$key],
                'qty' => $request->new_qty[$key]??$request->qty[$key],
                'date' => $request->date,
                'subtotal_on_product' =>  $request->new_subtotal[$key] ?? $request->subtotal[$key],
            ]);

                  // StockHistory
            if($data_add->location_form){

                $old_Stock  = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$data_add->location_form)->first();
                if( $old_Stock){
                    $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                    ]);
                }else{
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $data_add->location_form;
                    $stock['qty'] = $for_name_row->qty;
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }

                $stockOutHistory['item_id'] = $for_name_row->item_id;
                $stockOutHistory['out_qty'] = $for_name_row->qty;
                $stockOutHistory['godown_id'] = $data_add->location_form;
                $stockOutHistory['category_id'] = $for_name_row->item->category->id;
                $stockOutHistory['average_price'] = $for_name_row->price ;
                $data_add->stock()->create($stockOutHistory);
            }
            if($data_add->location_to){

                $old_p_Stock_qty = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$data_add->location_to)->first();
                if($old_p_Stock_qty){
                    $new_p_stock_qty = $old_p_Stock_qty->qty + $for_name_row->qty;
                    $old_p_Stock_qty->update([
                        'qty' => $new_p_stock_qty,
                    ]);
                }else{
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $data_add->location_to;
                    $stock['qty'] = $for_name_row->qty;
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }

                $stockHistory['item_id'] = $for_name_row->item_id;
                $stockHistory['in_qty'] = $for_name_row->qty;
                $stockHistory['godown_id'] = $data_add->location_to;
                $stockHistory['category_id'] = $for_name_row->item->category->id;
                $stockHistory['average_price'] = $for_name_row->price ;
                $data_add->stock()->create($stockHistory);
            }

        }
    }
    public function deleteDemoProductAddOnVoucher($request, $id )
    {
        $productsId =[];
        try {
            $data =  StockTransfer::where('id',$id)->with('stock')->first();
            $allProducts = DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->get(['item_id'])->toArray();
            foreach ($allProducts as $key => $value) {
                array_push($productsId, $value['item_id']);
            }

            if($request->item_id){
                $deletedProducts = array_diff($productsId, $request->item_id);
                if(count($deletedProducts) > 0 && $deletedProducts != null){

                    foreach ($deletedProducts as $key => $productId) {
                        StockHistory::where('stockable_id', $data->id)->where( "stockable_type", "App\StockTransfer")->
                        where('item_id', $productId)->where('godown_id', $data->location_form)->delete();
                        StockHistory::where('stockable_id', $data->id)->where( "stockable_type", "App\StockTransfer")->
                        where('item_id', $productId)->where('godown_id', $data->location_to)->delete();

                        $demoProductAddOnVoucher =DemoProductAddOnVoucher::where('product_id_list', $request->product_id_list)->where('item_id', $productId)->first();
                        $old_Stock  = StockDetail::where('item_id',$productId )->where('godown_id', $data->location_form)->first();

                        // location_form qty added
                        $new_stock_qty = $old_Stock->qty + $demoProductAddOnVoucher->qty;
                        $old_Stock->update([
                            'qty' => $new_stock_qty,
                        ]);

                        // location_to qty minus
                        $old_p_Stock_qty = StockDetail::where('item_id',$productId )->where('godown_id',$data->location_to)->first(); $new_p_stock_qty = $old_p_Stock_qty->qty -  $demoProductAddOnVoucher->qty;
                        $old_p_Stock_qty->update([
                            'qty' => $new_p_stock_qty,
                        ]);

                        $demoProductAddOnVoucher->delete();

                    }
                }
            }else{
                $for_name =  DemoProductAddOnVoucher::where('product_id_list',$request->product_id_list)->get();

                foreach ($for_name??[] as $for_name_row) {

                    // location_form qty added
                    $old_Stock  = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$data->location_form)->first();
                    $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                    ]);

                    // location_to qty minus
                    $old_p_Stock_qty = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$data->location_to)->first();
                    if($old_p_Stock_qty){
                        $new_p_stock_qty = $old_p_Stock_qty->qty - $for_name_row->qty;
                        $old_p_Stock_qty->update([
                            'qty' => $new_p_stock_qty,
                        ]);
                    }
                    $for_name_row->delete();

                }
            }

            } catch (\Exception $ex) {
                //dd($ex->getMessage());
            }
        return true;
    }

    public function delete_StockTransfer($product_id_list)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();

            $data =  StockTransfer::where('product_id_list',$product_id_list)->with('stock')->first();
            $for_name =  DemoProductAddOnVoucher::where('product_id_list',$product_id_list)->get();

            foreach ($for_name??[] as $for_name_row) {

                $old_Stock  = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$data->location_form)->first();
                $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                $old_Stock->update([
                    'qty' => $new_stock_qty,
                ]);
                $old_p_Stock_qty = StockDetail::where('item_id',$for_name_row->item_id )->where('godown_id',$data->location_to)->first();
                if($old_p_Stock_qty){
                    $new_p_stock_qty = $old_p_Stock_qty->qty - $for_name_row->qty;
                    $old_p_Stock_qty->update([
                        'qty' => $new_p_stock_qty,
                    ]);
                }
                $for_name_row->delete();
            }
            foreach ($data->stock as  $stock) {
                $stock->delete();
            }
            $data->delete();
            (new LogActivity)->addToLog('StockTransfer Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->to('stock_transfer_addlist');
    }

    public function stock_datatables()
    {
        $stock_details = StockAdjustment::with(['demo_stock_details'])->orderBy('date', 'desc');
        return Datatables()->eloquent($stock_details)
        ->addIndexColumn()
        ->addColumn('generated', function(StockAdjustment $stock_adjustment) {
            return $stock_adjustment->demo_stock_details->where("page_name", 1)->map(function($demo_stock) {
                return $demo_stock->item->name . "- ". $demo_stock->qty . " Pcs @".$demo_stock->subtotal_on_product . ".00 TK";
            })->implode('<br/>');
        })
        ->addColumn("consumed", function(StockAdjustment $stock_adjustment) {
            return $stock_adjustment->demo_stock_details->where("page_name", 2)->map(function($demo_stock) {
                return $demo_stock->item->name . "- ". $demo_stock->qty . " Pcs @".$demo_stock->subtotal_on_product . ".00 TK";
            })->implode('<br/>');
        })
        ->addColumn('action', function(StockAdjustment $stock_adjustment){
            return make_action_btn([
                '<a href="'.route("edit_stock_adjustment", ['adjustmen_vo_id' => $stock_adjustment->adjustmen_vo_id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="#" data-id="'.$stock_adjustment->adjustmen_vo_id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
            ]);
        })
        ->rawColumns(['generated', 'consumed', "action"])
        ->make(true);
    }

    //add Stock Adjustment .....................
    public function stock_adjustment_addlist(Request $request)
    {
        if($request->ajax()) 
        {
            return $this->stock_datatables();
        }
        return view('MBCorporationHome.transaction.stock_adjustment.index');
    }
    public function stock_adjustment_addlist_form()
    {
    	$Godwn = Godown::get();
		$Item = Item::get();
        return view('MBCorporationHome.transaction.stock_adjustment.stock_adjustment_addlist_form',compact('Item','Godwn'));
    }


    public function add_ondemoproduct_for_adjustment_store(Request $request)
    {

        try {
            $id_row = rand(111111,999999);
            $data_add =Demostockadjusment::updateOrCreate([
                'adjustmen_vo_id'=>$request->adjustmen_vo_id,
                'item_id'=>$request->item_name,
                'godown_id'=>$request->godown_id,
                'page_name'=>$request->page_name,
            ],[
                'id_row'=>$id_row,
                'page_name'=>$request->page_name,
                'price'=>$request->sales_price,
                'qty'=>$request->qty,
                'subtotal_on_product'=>$request->subtotal_on_product,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

         return response()->json($data_add);
    }

    public function product_new_fild_for_add_inStock($adjustmen_vo_id)
    {
        $data = Demostockadjusment::where('adjustmen_vo_id',$adjustmen_vo_id)->with(['item', 'godown'])->get();
        return response()->json($data);
    }
    public function adjustment_product_delete_fild_from_add($id_row)
    {
        // new add
        $info = Demostockadjusment::where('id_row',$id_row)->first();
        $StockAdjustment = StockAdjustment::where('adjustmen_vo_id', $info->adjustmen_vo_id)->first();
        $old_Stocks = StockDetail::where('item_id',$info->item_id)->where('godown_id', $info->godown_id)->first();
        if($info->page_name==1){
            $old_Stocks->update([
                'qty' => abs($old_Stocks->qty - $info->qty),
            ]);
        }
        if($info->page_name==2){
            $old_Stocks->update([
                'qty' => abs($old_Stocks->qty + $info->qty),
            ]);
        }
        StockHistory::where([
            ['stockable_id' , $StockAdjustment->id ],
            ['stockable_type' , 'App\StockAdjustment'],
            ['item_id' , $info->item_id],
            ['godown_id', $info->godown_id],
            ['page_name', $info->page_name],
        ])->delete();
        
        // new add end
        $data = Demostockadjusment::where('id_row',$id_row)->delete();
        return response()->json($data);
    }
    
    public function addDemostockadjusment_($request)
    {
        foreach ($request->item_id as $key => $item) {
            $id_row = rand(111111,999999);
            Demostockadjusment::create([
                'id_row'=>$id_row,
                'adjustmen_vo_id'=>$request->adjustmen_vo_id,
                'page_name'=>$request->page_name[$key],

                'godown_id'=>$request->godown_id[$key],
                'item_id'=>$item,
                'sales_price' => $request->price[$key],
                'qty' => $request->qty[$key],
                'subtotal_on_product' => $request->subtotal[$key],
            ]);
        }

        //return response()->json($data_add);
    }
    
   public function SaveAllData_adjusment_store(Request $request, Helper $helper)
    {
        $request->validate([
            'adjustmen_vo_id' => 'required|unique:stock_adjustments',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);
        try {
          DB::beginTransaction();

        $data_add = StockAdjustment::create([
            'date'=>$request->date,
            'adjustmen_vo_id'=>$request->adjustmen_vo_id,
            'refer_no'=>$request->refer_id,
        ]);
        
        $this->addDemostockadjusment_($request);
             
        
        $moves =  Demostockadjusment::where('adjustmen_vo_id',$request->adjustmen_vo_id)->where('page_name', 1)->get();
        if($moves->isNotEmpty()){
            foreach($moves as $for_name_row){
                $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();

                if ($old_Stock) {
                    $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'purchases_price' => $for_name_row->price,
                    ]);
                } else {
                    $stock['st_id'] = "ST" . rand(1111, 9999);
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] =  $for_name_row->godown_id;
                    $stock['qty'] =  $for_name_row->qty;
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }

                // StockHistory
                $stockHistory['item_id'] = $for_name_row->item_id;
                $stockHistory['in_qty'] = $for_name_row->qty;
                $stockHistory['page_name'] = 1;
                $stockHistory['godown_id'] = $for_name_row->godown_id;
                $stockHistory['category_id'] = $for_name_row->item->category->id;
                $stockHistory['average_price'] = (new Product)->average_price($for_name_row->item_id);
                $data_add->stock()->create($stockHistory);

                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                if ($itemCount) {
                    $itemCount->update(['generated_qty' => $for_name_row->qty + $itemCount->generated_qty,  'avg_purchase'=>(new Product)->average_price($for_name_row->item_id)]);
                } else {
                    ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['generated_qty' =>$for_name_row->qty ?? 0,
                    'avg_purchase'=> (new Product)->average_price($for_name_row->item_id)]);
                }
            }
        }

        $removes =  Demostockadjusment::where('adjustmen_vo_id',$request->adjustmen_vo_id)->where('page_name', 2)->get();
       
        if($removes->isNotEmpty()){
       
            foreach($removes as $for_name_row){
                $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();

                if ($old_Stock) {
                    $new_stock_qty = abs($old_Stock->qty - $for_name_row->qty);
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'purchases_price' => $for_name_row->price,
                    ]);
                } else {
                    $stock['st_id'] = "ST" . rand(1111, 9999);
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $for_name_row->godown_id;
                    $stock['qty'] =  (-1*$for_name_row->qty);
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }
                
                // StockHistory
                $stockOutHistory['item_id'] = $for_name_row->item_id;
                $stockOutHistory['out_qty'] = $for_name_row->qty;
                $stockOutHistory['page_name'] = 2;
                $stockOutHistory['godown_id'] = $for_name_row->godown_id;
                $stockOutHistory['category_id'] = $for_name_row->item->category->id;
                $stockOutHistory['average_price'] = (new Product)->average_price($for_name_row->item_id);
                $data_add->stock()->create($stockOutHistory);
                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                
                if ($itemCount) {
                    $itemCount->update(['consumed_qty' => $for_name_row->qty + $itemCount->consumed_qty,  'avg_purchase'=>(new Product)->average_price($for_name_row->item_id)]);
                } else {
                    ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['consumed_qty' =>$for_name_row->qty ?? 0,
                    'avg_purchase'=> (new Product)->average_price($for_name_row->item_id)]);
                }

            }
           
        }
    
        DB::commit();
      } catch (\Exception $ex) {
        Db::rollBack();
        dd($ex);

      }
    
        return redirect()->to('stock_adjustment_addlist');
    }


    public function edit_stock_adjustment($adjustmen_vo_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        $Godwn = Godown::get();
        $Item = Item::get();
        $adjustmen_no = $adjustmen_vo_id;

        return view('MBCorporationHome.transaction.stock_adjustment.edit_stock_adjustment', compact('adjustmen_no','Item','Godwn'));
    }

    public function Updatestock_adjustment__(Request $request,Helper $helper, $adjustmen_vo_id)
    {

        $request->validate([
            'adjustmen_vo_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
            StockAdjustment::where('adjustmen_vo_id',$adjustmen_vo_id)->update([
                'date'=>$request->date,
                'refer_no'=>$request->refer_id,
            ]);
            //dd('something wrong in data entry. this version not supported for it');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return view('MBCorporationHome.transaction.stock_adjustment.index');
    }
    
    public function Updatestock_adjustment(Request $request,Helper $helper, $adjustmen_vo_id)
    { 
        $request->validate([
            'adjustmen_vo_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        $StockAdjustment = StockAdjustment::where('adjustmen_vo_id',$adjustmen_vo_id)->first();
        try {
            DB::beginTransaction();
            $data_add = StockAdjustment::where('adjustmen_vo_id',$adjustmen_vo_id)->update([
                 'date'=>$request->date,
                 'refer_no'=>$request->refer_id,
            ]);

            $moves =  Demostockadjusment::where('adjustmen_vo_id',$request->adjustmen_vo_id)->where('page_name', 1)->get();
            if($moves){
                foreach($moves as $for_name_row){
                    $old_Stocks = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();
                    $Stock_history = StockHistory::where([
                        ['stockable_id' , $StockAdjustment->id ],
                        ['stockable_type' , 'App\StockAdjustment'],
                        ['item_id' , $for_name_row->item_id],
                        ['godown_id', $for_name_row->godown_id],
                        ['page_name', 1],
                    ])->first();
                    //echo $Stock_history->qty;
                    if ($old_Stocks) {
                        if($Stock_history){
                            $old_Stocks->update([
                                'qty' => abs($old_Stocks->qty - $Stock_history->in_qty) + $for_name_row->qty,
                                'purchases_price' => $for_name_row->price,
                            ]);
                            $Stock_history->in_qty >0?$Stock_history->delete():'';
                        }else{
                            $old_Stocks->update([
                                'qty' => abs($old_Stocks->qty + $for_name_row->qty),
                                'purchases_price' => $for_name_row->price,
                            ]);
                        }
                        
                        
                        
                    } else {
                        $stock['st_id'] = "ST" . rand(1111, 9999);
                        $stock['item_id'] = $for_name_row->item_id;
                        $stock['godown_id'] = $request->godown_id; 
                        $stock['qty'] =  $for_name_row->qty;
                        $stock['purchases_price'] =  $for_name_row->price;
                        StockDetail::create($stock);
                    }
                    //$Stock_history->delete();
                    // StockHistory
                    $stockHistory['stockable_id'] = $StockAdjustment->id;
                    $stockHistory['stockable_type'] = 'App\StockAdjustment';
                    $stockHistory['item_id'] = $for_name_row->item_id;
                    $stockHistory['in_qty'] = $for_name_row->qty;
                    $stockHistory['page_name'] = 1;
                    $stockHistory['godown_id'] = $for_name_row->godown_id;
                    $stockHistory['category_id'] = $for_name_row->item->category->id;
                    $stockHistory['average_price'] = (new Product)->average_price($for_name_row->item_id);
                    StockHistory::create($stockHistory);

                    /*$itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['generated_qty' => $for_name_row->qty + $itemCount->generated_qty,  'avg_purchase'=>(new Product)->average_price($for_name_row->item_id)]);
                    } else {
                        ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['generated_qty' =>$for_name_row->qty ?? 0,
                        'avg_purchase'=> (new Product)->average_price($for_name_row->item_id)]);
                    }*/
                }
            }
            $remove =  Demostockadjusment::where('adjustmen_vo_id',$request->adjustmen_vo_id)->where('page_name', 2)->get();
            if($remove){
                foreach($remove as $for_name_row){
                    $old_Stocks = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();
                    $Stock_history = StockHistory::where([
                        ['stockable_id' , $StockAdjustment->id ],
                        ['stockable_type' , 'App\StockAdjustment'],
                        ['item_id' , $for_name_row->item_id],
                        ['godown_id', $for_name_row->godown_id],
                        ['page_name', 2],
                    ])->first();
                    if ($old_Stocks) {

                        if($Stock_history){
                            $old_Stocks->update([
                                'qty' => abs(($old_Stocks->qty + $Stock_history->out_qty) - $for_name_row->qty),
                                'purchases_price' => $for_name_row->price,
                            ]);
                            $Stock_history->out_qty >0?$Stock_history->delete():'';
                        }else{
                            $old_Stocks->update([
                                'qty' => abs($old_Stocks->qty - $for_name_row->qty),
                                'purchases_price' => $for_name_row->price,
                            ]);
                        }

                    } else {
                        $stock['st_id'] = "ST" . rand(1111, 9999);
                        $stock['item_id'] = $for_name_row->item_id;
                        $stock['godown_id'] = $request->godown_id; 
                        $stock['qty'] =  $for_name_row->qty;
                        $stock['purchases_price'] =  $for_name_row->price;
                        StockDetail::create($stock);
                    }
                    
                    // StockHistory
                    $stockHistory2['stockable_id'] = $StockAdjustment->id;
                    $stockHistory2['stockable_type'] = 'App\StockAdjustment';
                    $stockHistory2['item_id'] = $for_name_row->item_id;
                    $stockHistory2['out_qty'] = $for_name_row->qty;
                    $stockHistory2['page_name'] = 2;
                    $stockHistory2['godown_id'] = $for_name_row->godown_id;
                    $stockHistory2['category_id'] = $for_name_row->item->category->id;
                    $stockHistory2['average_price'] = (new Product)->average_price($for_name_row->item_id);
                    StockHistory::create($stockHistory2);

                    /*$itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['generated_qty' => $for_name_row->qty + $itemCount->generated_qty,  'avg_purchase'=>(new Product)->average_price($for_name_row->item_id)]);
                    } else {
                        ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['generated_qty' =>$for_name_row->qty ?? 0,
                        'avg_purchase'=> (new Product)->average_price($for_name_row->item_id)]);
                    }*/
                }
            }
            //dd('something wrong in data entry. this version not supported for it');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return view('MBCorporationHome.transaction.stock_adjustment.index');
    }

    public function delete_stock_adjustment($adjustmen_vo_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        
        $StockAdjustment = StockAdjustment::where('adjustmen_vo_id', $adjustmen_vo_id)->first();

        $olddata = Demostockadjusment::where('adjustmen_vo_id',$adjustmen_vo_id)->where('page_name', 1)->get();

        if($olddata){
            foreach($olddata as $for_name_row2){
                $StockHistory = StockHistory::where([
                    ['stockable_id' , $StockAdjustment->id ],
                    ['stockable_type' , 'App\StockAdjustment'],
                    ['item_id' , $for_name_row2->item_id],
                    ['godown_id', $for_name_row2->godown_id],
                ])->first();

                $old_Stock =StockDetail::where('item_id',$for_name_row2->item_id)->where('godown_id', $for_name_row2->godown_id)->first();
                
                $old_Stock->update([
                    'qty' => abs($old_Stock->qty - $for_name_row2->qty)
                ]);

                $StockHistory->delete();
            }
        }

        $olddata2 = Demostockadjusment::where('adjustmen_vo_id',$adjustmen_vo_id)->where('page_name', 2)->get();
        if($olddata2){
            foreach($olddata2 as $for_name_row2){
                $StockHistory = StockHistory::where([
                    ['stockable_id' , $StockAdjustment->id ],
                    ['stockable_type' , 'App\StockAdjustment'],
                    ['item_id' , $for_name_row2->item_id],
                    ['godown_id', $for_name_row2->godown_id],
                ])->first();

                $old_Stock =StockDetail::where('item_id',$for_name_row2->item_id)->where('godown_id', $for_name_row2->godown_id)->first();
                
                $old_Stock->update([
                    'qty' => abs($old_Stock->qty + $for_name_row2->qty)
                ]);

                $StockHistory->delete();
            }
        }

        Demostockadjusment::where('adjustmen_vo_id', $adjustmen_vo_id)->delete();
        StockAdjustment::where('adjustmen_vo_id', $adjustmen_vo_id)->delete();
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
    }


}
