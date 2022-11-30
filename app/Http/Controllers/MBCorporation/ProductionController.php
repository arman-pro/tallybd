<?php

namespace App\Http\Controllers\MBCorporation;

use App\DemoProductProduction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Godown;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Helpers\Product;
use App\Item;
use App\ItemCount;
use App\Production;
use App\StockDetail;
use App\StockHistory;
use Session;
use App\WorkingOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Datatables;

class ProductionController extends Controller
{
    public function datatables()
    {
        $productions = Production::with(['working'])->orderBy('date', 'desc');
        return Datatables()->eloquent($productions)
        ->addIndexColumn()
        ->editColumn('date', function(Production $production) {
            return date('d-m-y', strtotime($production->date));
        })
        ->addColumn('items', function(Production $production) {
            return $production->demo_product_productions->map(function($demo_product) {
                return $demo_product->item->name .", " . $demo_product->qty . " @ " . $demo_product->subtotal_on_product . " ";
            })->implode('<br/>');
        })
        ->addColumn('working_vo_no', function(Production $production) {
            return optional($production->working)->vo_no ?? 'No Working Id';
        })
        ->addColumn('action', function(Production $production) {
            return make_action_btn([
                '<a href="'.route("production.edit",['id' => $production->vo_no]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="javascript:void(0)" data-id="'.$production->vo_no.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("production.print", ['id' => $production->id]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['items', 'working_vo_no', 'action'])
        ->make(true);
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->datatables();
        }
        return view('MBCorporationHome.production.index');
    }

    public function print($id)
    {
        $productionOrder= Production::with('working')->find($id);
        $workingOrder=$productionOrder->working;
        $Working= WorkingOrder::where('id', $productionOrder->working_id)->first();
        $costinfo = DB::table('working_order_costinfo')->where('vo_no', $Working->vo_no)->get();
        
        return view('MBCorporationHome.production.print', compact('productionOrder', 'workingOrder', 'costinfo'));
    }

    public function production_adjustment($request)
    {
        try {
            DB::beginTransaction();
            $id_row = rand(111111,999999).'-'.date('y');
            foreach ($request->item_id as $key => $item_id) {
                // $demo =DemoProductProduction::updateOrCreate([
                //     'vo_no'=>$request->adjustmen_vo_id,
                //     'item_id'=>$item_id,
                //     'godown_id'=>$request->godown_id[$key],
                //     'page_name'=>2,
                // ],[
                //     'id_row'=>$id_row,
                //     'page_name'=>2,
                //     'price'=>$request->price[$key],
                //     'qty'=>$request->qty[$key],
                //     'subtotal_on_product'=>$request->subtotal[$key],
                // ]);
                $demo =DemoProductProduction::create([
                    'vo_no'=>$request->adjustmen_vo_id,
                    'item_id'=>$item_id,
                    'godown_id'=>$request->godown_id[$key],
                    'page_name'=>2,
                    'id_row'=>$id_row,
                    'page_name'=>2,
                    'price'=>$request->price[$key],
                    'qty'=>$request->qty[$key],
                    'subtotal_on_product'=>$request->subtotal[$key],
                ]);
            }
            // dd($demo);



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
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
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
        $godowns = Godown::get();
		$Item = Item::get();
        $working_order = WorkingOrder::where('status', true)->whereNull('production_id')->get(['id', 'vo_no']);
        $order_list = [];
        $order_costinfo = [];
        return view('MBCorporationHome.production.create',compact('order_list','Item','godowns', 'working_order', 'order_costinfo'));
    }

    public function orderList(Request $request)
    {
        // dd($request->working_id);
        $godowns = Godown::get();
		$Item = Item::get();
        $working_order = WorkingOrder::where('status', true)->whereNull('production_id')->get(['id', 'vo_no']);
        $order_list = WorkingOrder::where('id', $request->working_id)->with('stock')->first();
        $order_costinfo = DB::table('working_order_costinfo')->where('vo_no', $order_list->vo_no)->get();
        
        return view('MBCorporationHome.production.create',compact('order_list','Item','godowns', 'working_order', 'order_costinfo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Helper $helper)
    {

        $validator = Validator::make($request->all(),[
            'adjustmen_vo_id' => 'required|unique:productions,vo_no',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
            'working_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $data_add = Production::create([
                'date'=>$request->date,
                'vo_no'=>$request->adjustmen_vo_id,
                'refer_no'=>$request->refer_id,
                'working_id'=>$request->working_id,
                'total'=>array_sum($request->subtotal),
            ]);
            
            $this->production_adjustment($request);
            WorkingOrder::whereId( $request->working_id)->first()->update(['production_id' => $data_add->id]);
            $removes =  DemoProductProduction::where('vo_no',$request->adjustmen_vo_id)->where('page_name', 2)->get();


            foreach($removes as $for_name_row){

                $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();

                if ($old_Stock) {
                    $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'purchases_price' => $for_name_row->price,
                    ]);
                } else {
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $for_name_row->item_id;
                    $stock['godown_id'] = $for_name_row->godown_id;
                    $stock['qty'] =  ($for_name_row->qty);
                    $stock['purchases_price'] =  $for_name_row->price;
                    StockDetail::create($stock);
                }

                // StockHistory
                $stockInHistory['item_id'] = $for_name_row->item_id;
                $stockInHistory['in_qty'] = $for_name_row->qty;
                $stockInHistory['godown_id'] = $for_name_row->godown_id;
                $stockInHistory['category_id'] = $for_name_row->item->category->id;
                $stockInHistory['average_price'] = $for_name_row->price;
                $data_add->stock()->create($stockInHistory);

                $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                if ($itemCount) {
                    $itemCount->update(['production_qty' => $for_name_row->qty + $itemCount->production_qty]);
                } else {
                    ItemCount::updateOrCreate(['item_id' => $for_name_row->id], ['production_qty' =>$for_name_row->qty ]);
                }
            }


            (new LogActivity)->addToLog('Production Store.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json( $ex->getMessage(), $ex->getLine());
        }
        if($request->print){
           return $this->print($data_add->id);
        }
        return redirect()->to('production/index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $working_order = WorkingOrder::where('status', true)->whereNotNull('production_id')->get(['id', 'vo_no']);
        $production= Production::where('vo_no', $vo_no)->with('working.stock')->first();
        return view('MBCorporationHome.production.edit', compact('working_order','vo_no','Item','godowns', 'production'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Helper $helper, $vo_no)
    {

        $validator = Validator::make($request->all(),[
            'adjustmen_vo_id' => 'required|unique:productions,vo_no,' .$vo_no,
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
            'working_id' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $production = Production::where('vo_no',$vo_no)->first();
            
            if($production->date != $request->date) {
                $production->date = $request->date;
                $production->save();
            }
            
            if($request->item_id){
                $productsId =[];
                $allProducts = DemoProductProduction::where('vo_no', $request->adjustmen_vo_id)->get(['item_id'])->toArray();
                foreach ($allProducts as $key => $value) {
                    array_push($productsId, $value['item_id']);
                }
                $deletedProducts = array_diff($productsId, $request->item_id);

                foreach ($deletedProducts as $key => $item_id) {
                    $singleRemove =  DemoProductProduction::where('vo_no',$production->vo_no)->where('item_id', $item_id)->where('page_name', 2)->first();
                    $old_Stock = StockDetail::where('item_id',$singleRemove->item_id)->where('godown_id', $singleRemove->godown_id)->first();
                    $new_stock_qty = $old_Stock->qty - $singleRemove->qty;
                    $old_Stock->update([
                        'qty' => $new_stock_qty,
                        'purchases_price' => $singleRemove->price,
                        'date' => $request->date,
                    ]);

                    StockHistory::where([
                        ['item_id' , $singleRemove->item_id],['godown_id', $singleRemove->godown_id],['stockable_type',  'App\Production'],['stockable_id' , $production->id]
                    ])->delete();
                     $itemCount = ItemCount::where('item_id', $singleRemove->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['production_qty' => $itemCount->production_qty - $singleRemove->qty ]);
                    }
                    // dd( $itemCount);
                    $singleRemove->delete();
                }
            }elseif(empty($request->item_id)){

                $removes =  DemoProductProduction::where('vo_no',$request->adjustmen_vo_id)->where('page_name', 2)->get();

                if($removes){
                    foreach($removes as $for_name_row){
                        $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();

                        if ($old_Stock) {
                            if( $old_Stock->qty > 0){
                                $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                            }else{
                                $new_stock_qty = $old_Stock->qty + $for_name_row->qty;
                            }
                            $old_Stock->update([
                                'qty' => $new_stock_qty,
                                'purchases_price' => $for_name_row->price,
                                'date' => $request->date,
                            ]);
                        }

                        StockHistory::where([
                            ['item_id' , $for_name_row->item_id],['godown_id', $for_name_row->godown_id],['stockable_type',  'App\Production'],['stockable_id' , $production->id]
                        ])->delete();


                        $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                        if ($itemCount) {
                            $itemCount->update(['production_qty' => $itemCount->production_qty - $for_name_row->qty]);
                        }

                        $for_name_row->delete();

                    }
                }

            }
            if($request->new_item_id){
                $this->new_production_adjustment($request, $production);
            }
            
            $demo_productions = DemoProductProduction::where('vo_no', $production->vo_no)->get();
            foreach($demo_productions as $demo_production) {
                $demo_production->date = $request->date;
                $demo_production->save();
                $history = StockHistory::where('item_id', $demo_production->item_id)->where('stockable_id', $production->id)->first();
                $history->date = $request->date;
                $history->save();
            }
            

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;

        }
        if($request->print){
            return $this->print($production->id);
         }
        return redirect()->to('production/index');
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
                //     'page_name'=>2,
                // ],[
                //     'id_row'=>$id_row,
                //     'page_name'=>2,
                //     'price'=>$request->new_price[$key],
                //     'qty'=>$request->new_qty[$key],
                //     'subtotal_on_product'=>$request->new_subtotal[$key],
                //     'date' => $request->date,
                // ]);
                $demo =DemoProductProduction::updateOrCreate([
                    'vo_no'=>$request->adjustmen_vo_id,
                    'item_id'=>$item_id,
                    'godown_id'=>$request->new_godown_id[$key],
                    'page_name'=>2,
                    'id_row'=>$id_row,
                    'page_name'=>2,
                    'price'=>$request->new_price[$key],
                    'qty'=>$request->new_qty[$key],
                    'subtotal_on_product'=>$request->new_subtotal[$key],
                    'date' => $request->date,
                ]);


                $old_Stock = StockDetail::where('item_id',$demo->item_id)->where('godown_id', $demo->godown_id)->first();

                    if ($old_Stock) {
                        $new_stock_qty = $old_Stock->qty + $demo->qty;
                        $old_Stock->update([
                            'qty' => $new_stock_qty,
                            'purchases_price' => $demo->price,
                            'date' => $request->date,
                        ]);
                    } else {
                        $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                        $stock['item_id'] = $demo->item_id;
                        $stock['godown_id'] = $demo->godown_id;
                        $stock['qty'] =  ($demo->qty);
                        $stock['purchases_price'] =  $demo->price;
                        $stock['date'] = $request->date;
                        StockDetail::create($stock);
                    }

                    // StockHistory
                    $stockOutHistory['item_id'] = $demo->item_id;
                    $stockOutHistory['in_qty'] = $demo->qty;
                    $stockOutHistory['godown_id'] = $demo->godown_id;
                    $stockOutHistory['category_id'] = $demo->item->category->id;
                    $stockOutHistory['average_price'] = $demo->price;
                    $data_add->stock()->create($stockOutHistory);

                    $itemCount = ItemCount::where('item_id', $demo->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['production_qty' => $demo->qty + $itemCount->production_qty]);
                    } else {
                        ItemCount::updateOrCreate(['item_id' => $demo->item_id], ['production_qty' =>$demo->qty]);
                    }
            }
            // dd($demo);



           DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }
        return true;
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
        
        // try {
            
        //     DB::beginTransaction();
           
            $Production = Production::where('vo_no',$id)->with('stock', 'working')->first();
            // $Production->working->first()->update(['production_id' => null]);
            $working = WorkingOrder::find($Production->working_id);
            $working->production_id = null;
            $working->save();
            
            $removes =  DemoProductProduction::where('vo_no',$Production->vo_no)->where('page_name', 2)->get();

            if($removes){
                foreach($removes as $for_name_row){
                    $old_Stock = StockDetail::where('item_id',$for_name_row->item_id)->where('godown_id', $for_name_row->godown_id)->first();
                    if ($old_Stock) {
                        $new_stock_qty = $old_Stock->qty - $for_name_row->qty;
                        $old_Stock->update([
                            'qty' => $new_stock_qty,
                            'purchases_price' => $for_name_row->price,
                        ]);
                    }

                    StockHistory::where([
                        ['item_id' , $for_name_row->item_id],['godown_id', $for_name_row->godown_id],['stockable_type',  'App\Production'],['stockable_id' , $Production->id]
                    ])->delete();


                    $itemCount = ItemCount::where('item_id', $for_name_row->item_id)->first();
                    if ($itemCount) {
                        $itemCount->update(['production_qty' => $itemCount->production_qty - $for_name_row->qty]);
                    }
                    $for_name_row->delete();

                }
            }
            
            $Production->working_id = null;
            $Production->save();
            $res = Production::find($Production->id);
            $res = $res->delete();
            return response()->json(['status' => $res]);
        //     DB::commit();
        //  } catch (\Throwable $th) {
        //      DB::rollBack();
        //      throw $th;
        // }
        //  return back();
    }
}
