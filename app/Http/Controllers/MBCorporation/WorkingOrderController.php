<?php

namespace App\Http\Controllers\MBCorporation;

use App\DemoProductProduction;
use App\Godown;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Helpers\Product;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemCount;
use App\StockDetail;
use App\StockHistory;
use App\WorkingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Session;
class WorkingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('MBCorporationHome.workingorder.index');
    }


    public function print($id)
    {
        $workingOrder= WorkingOrder::find($id);
        $costinfo = DB::table('working_order_costinfo')->where('vo_no', $workingOrder->vo_no)->get();
        return view('MBCorporationHome.workingorder.print', compact('workingOrder', 'costinfo'));

    }
    public function production_adjustment($request)
    {
        try {
            DB::beginTransaction();
            $id_row = rand(111111,999999).'-'.date('y');
            foreach ($request->item_id as $key => $item_id) {
                // $demo = DemoProductProduction::updateOrCreate([
                //     'vo_no'=>$request->adjustmen_vo_id,
                //     'item_id'=>$item_id,
                //     'godown_id'=>$request->godown_id[$key],
                //     'page_name'=>1,
                // ],[
                //     'id_row'=>$id_row,
                //     'page_name'=>1,
                //     'price'=>$request->price[$key],
                //     'qty'=>$request->qty[$key],
                //     'subtotal_on_product'=>$request->subtotal[$key],
                // ]);
                $demo = DemoProductProduction::create([
                    'vo_no'=>$request->adjustmen_vo_id,
                    'item_id'=>$item_id,
                    'godown_id'=>$request->godown_id[$key],
                    'page_name'=>1,
                    'id_row'=>$id_row,
                    'page_name'=>1,
                    'price'=>$request->price[$key],
                    'qty'=>$request->qty[$key],
                    'subtotal_on_product'=>$request->subtotal[$key],
                ]);
            }


           DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }
        return true;
    }
    public function  new_production_adjustment($request, $data_add)
    {
      
        try {
            DB::beginTransaction();
            $id_row = rand(111111,999999).'-'.date('y');
            foreach ($request->new_item_id as $key => $item_id) {
                // $demo =DemoProductProduction::updateOrCreate([
                //     'vo_no'=>$request->adjustmen_vo_id,
                //     'item_id'=>$item_id,
                //     'godown_id'=>$request->new_godown_id[$key],
                //     'page_name'=>1,
                // ],[
                //     'id_row'=>$id_row,
                //     'page_name'=>1,
                //     'price'=>$request->new_price[$key],
                //     'qty'=>$request->new_qty[$key],
                //     'subtotal_on_product'=>$request->new_subtotal[$key],
                // ]);
                $demo =DemoProductProduction::updateOrCreate([
                    'vo_no'=>$request->adjustmen_vo_id,
                    'item_id'=>$item_id,
                    'godown_id'=>$request->new_godown_id[$key],
                    'page_name'=>1,
                    'id_row'=>$id_row,
                    'page_name'=>1,
                    'price'=>$request->new_price[$key],
                    'qty'=>$request->new_qty[$key],
                    'subtotal_on_product'=>$request->new_subtotal[$key],
                ]);


                $old_Stock = StockDetail::where('item_id',$demo->item_id)->where('godown_id', $demo->godown_id)->first();

                    if ($old_Stock) {
                        $new_stock_qty = $old_Stock->qty - $demo->qty;
                        $old_Stock->update([
                            'qty' => $new_stock_qty,
                            'purchases_price' => $demo->price,
                        ]);
                    } else {
                        $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                        $stock['item_id'] = $demo->item_id;
                        $stock['godown_id'] = $demo->godown_id;
                        $stock['qty'] =  (-1*$demo->qty);
                        $stock['purchases_price'] =  $demo->price;
                        StockDetail::create($stock);
                    }

                    // StockHistory
                    $stockOutHistory['item_id'] = $demo->item_id;
                    $stockOutHistory['out_qty'] = $demo->qty;
                    $stockOutHistory['godown_id'] = $demo->godown_id;
                    $stockOutHistory['category_id'] = $demo->item->category->id;
                    $stockOutHistory['average_price'] = $demo->price;
                    $data_add->stock()->create($stockOutHistory);

                    $itemCount = ItemCount::where('item_id', $demo->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['working_qty' => $demo->qty + $itemCount->working_qty]);
                    } else {
                        ItemCount::updateOrCreate(['item_id' => $demo->id], ['working_qty' =>$demo->qty]);
                    }
            }




           DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }
        return true;
    }

    public function findProductRow(Request $request)
    {
        $data = DemoProductProduction::where('vo_no',$request->vo_no)->with(['item', 'godown'])->get();
        return response()->json($data);
    }

    public function delete_field_from_add($id_row)
    {
        $data = DemoProductProduction::where('id_row',$id_row)->delete();
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Godwn = Godown::get();
		$Item = Item::get();
        return view('MBCorporationHome.workingorder.create',compact('Item','Godwn'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function working_ordersExtraCost($request, $status=false)
    {
        if($status){
            DB::table('working_order_costinfo')->where('vo_no', $request->adjustmen_vo_id)->delete();
        }
         if(is_array($request->e_title)){
            foreach ($request->e_title as $key => $title) {
                DB::table('working_order_costinfo')->insert([
                    'title'=>$title,
                    'price'=>$request->e_price[$key],
                    'qty'=>$request->e_qty[$key],
                    'total'=>$request->e_price[$key]*$request->e_qty[$key],
                    'vo_no'=>$request->adjustmen_vo_id,
                ]);
            }
         }
    }

     
    public function store(Request $request, Helper $helper)
    {
        // dd($request->all());
        $request->validate([
            'adjustmen_vo_id' => 'required|unique:working_orders,vo_no',
            'item_id.*' => 'required',
            'godown_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);
        try {
            DB::beginTransaction();
            $this->production_adjustment($request);
            $data_add = WorkingOrder::create([
                'date'=>$request->date,
                'vo_no'=>$request->adjustmen_vo_id,
                'refer_no'=>$request->refer_id,
            ]);
    
            $this->working_ordersExtraCost($request);

            $removes =  DemoProductProduction::where('vo_no',$request->adjustmen_vo_id)->where('page_name', 1)->get();

            if($removes){
                foreach($removes as $for_name_row){
                    $old_Stock = StockDetail::where('item_id', $for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();

                    if ($old_Stock) {
                        $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                        $old_Stock->update([
                            'qty' => $new_stock_qty,
                            'purchases_price' => $for_name_row->price,
                        ]);
                    } else {
                        $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                        $stock['item_id'] = $for_name_row->item_id;
                        $stock['godown_id'] = $for_name_row->godown_id;
                        $stock['qty'] =  (-1*$for_name_row->qty);
                        $stock['purchases_price'] =  $for_name_row->price;
                        StockDetail::create($stock);
                    }
                    
                    // StockHistory
                    $stockOutHistory['item_id'] = $for_name_row->item_id;
                    $stockOutHistory['out_qty'] = $for_name_row->qty;
                    $stockOutHistory['godown_id'] = $for_name_row->godown_id;
                    $stockOutHistory['category_id'] = $for_name_row->item->category->id;
                    $stockOutHistory['average_price'] = $for_name_row->price;
                    $data_add->stock()->create($stockOutHistory);

                    $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['working_qty' => $for_name_row->qty + $itemCount->working_qty]);
                    } else {
                        ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['working_qty' =>$for_name_row->qty]);
                    }
                    
                }
            }
             (new LogActivity)->addToLog('WorkingOrder Created.');

            DB::commit();
        } catch (\Throwable $ex) {
            DB::rollBack();
            throw $ex;
        }
        if($request->print){
            return $this->print( $data_add->id);
        }
        return redirect()->to('workingorder/index');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($vo_no)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $godowns = Godown::get();
        $Item = Item::get();
        $vo_no = $vo_no;
        $workingOrder =WorkingOrder::where('vo_no', $vo_no)->first();
        $extracoat = DB::table('working_order_costinfo')->where('vo_no', $vo_no)->get();
        return view('MBCorporationHome.workingorder.edit', compact('vo_no','Item','godowns', 'workingOrder', 'extracoat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Helper $helper,$vo_no)
    {
       
        $request->validate([
            //'adjustmen_vo_id' => 'required|unique:working_orders,vo_no',
            'adjustmen_vo_id' => 'required',
            'item_id.*' => 'required',
            //'godown_id' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);
       
        $this->working_ordersExtraCost($request, true);
        
        try {
            DB::beginTransaction();
            $workingOrder = WorkingOrder::where('vo_no',$vo_no)->with('stock')->first();
            $workingOrder->update(['date'=>$request->date]);
            $aa = StockHistory::where([['stockable_type',  'App\WorkingOrder'],['stockable_id' , $workingOrder->id]])->update(['date'=>$request->date]);
            
            if($request->item_id){
                $productsId =[];
                $allProducts = DemoProductProduction::where('vo_no', $request->adjustmen_vo_id)->get(['item_id']);

                foreach ($allProducts as $key => $value) {
                    array_push($productsId, $value['item_id']);
                }
                $deletedProducts = array_diff($productsId, $request->item_id);

                if(count($deletedProducts)> 0){

                    foreach ($deletedProducts as $key => $item_id) {
                        $singleRemove =  DemoProductProduction::where('vo_no',$workingOrder->vo_no)->where('item_id', $item_id)->where('page_name', 1)->first();
                        // dd($singleRemove);
                        $old_Stock = StockDetail::where('item_id',$singleRemove->item_id)->where('godown_id', $singleRemove->godown_id)->first();
                        $new_stock_qty = $old_Stock->qty + $singleRemove->qty;
                        $old_Stock->update([
                            'qty' => $new_stock_qty,
                            'purchases_price' => $singleRemove->price,
                        ]);
                        StockHistory::where([
                            ['item_id' , $singleRemove->item_id],['godown_id', $singleRemove->godown_id],['stockable_type',  'App\WorkingOrder'],['stockable_id' , $workingOrder->id]
                        ])->delete();
                        $itemCount = ItemCount::where('item_id', $singleRemove->item_id)->first();
                        if ($itemCount) {
                            $itemCount->update(['working_qty' => $itemCount->working_qty - $singleRemove->qty ]);
                        }
                        $singleRemove->delete();
                    }
                }

            }else{
                $removes =  DemoProductProduction::where('vo_no',$request->adjustmen_vo_id)->where('page_name', 1)->get();

                if($removes){
                    foreach($removes as $for_name_row){
                        $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();
                        if ($old_Stock) {
                            $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                            $old_Stock->update([
                                'qty' => $new_stock_qty,
                                'purchases_price' => $for_name_row->price,
                            ]);
                        }

                        $v =StockHistory::where([
                            ['item_id' , $for_name_row->item_id],['godown_id', $for_name_row->godown_id],['stockable_type',  'App\WorkingOrder'],['stockable_id' , $workingOrder->id]
                        ])->delete();


                        $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                        if ($itemCount) {
                            $itemCount->update(['working_qty' => $itemCount->working_qty -$for_name_row->qty ]);
                        }
                        $for_name_row->delete();

                    }
                }
            }
            if($request->new_item_id){
                $this->new_production_adjustment($request, $workingOrder);
            }

            if($request->print){
                return $this->print( $workingOrder->id);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;

        }
        return redirect()->to('workingorder/index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
         try {
           DB::beginTransaction();
           $workingOrder = WorkingOrder::where('vo_no',$id)->with('stock')->first();

           $removes =  DemoProductProduction::where('vo_no',$workingOrder->vo_no)->where('page_name', 1)->get();
           DB::table('working_order_costinfo')->where('vo_no', $workingOrder->vo_no)->delete();
           if($removes){
               foreach($removes as $for_name_row){
                   $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();
                   if ($old_Stock) {
                       $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                       $old_Stock->update([
                           'qty' => $new_stock_qty,
                           'purchases_price' => $for_name_row->price,
                       ]);
                   }

                   $v =StockHistory::where([
                       ['item_id' , $for_name_row->item_id],['godown_id', $for_name_row->godown_id],['stockable_type',  'App\WorkingOrder'],['stockable_id' , $workingOrder->id]
                   ])->delete();


                   $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                   if ($itemCount) {
                       $itemCount->update(['working_qty' => $itemCount->working_qty -$for_name_row->qty ]);
                   }
                   $for_name_row->delete();

               }
           }
           $workingOrder->delete();
           DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

    }
}
