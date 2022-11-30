<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon;
use App\Category;
use App\Unit;
use App\Item;
use App\Godown;
use App\Helpers\LogActivity;
use App\Helpers\Product;
use App\ItemCount;
use App\StockDetail;
use App\StockHistory;
use Illuminate\Support\Facades\DB;
use Session;
use App\Helpers\Helper;

class ItemController extends Controller
{
    public function item_datatables() 
    {
        $items = Item::with(['category'])->orderBy('id', 'desc');
        return Datatables()->eloquent($items)
        ->addIndexColumn()
        ->addColumn('category', function(Item $item) {
            return optional($item->category)->name ?? "N/A";
        })
        ->editColumn('unit', function(Item $item) {
            return optional($item->category)->name ?? "N/A";
        })
        ->editColumn('purchases_price', function(Item $item) {
            return new_number_format($item->purchases_price, 2);
        })
        ->editColumn('sale_price', function(Item $item) {
            return new_number_format($item->sales_price, 2);
        })
        ->editColumn('previouse_stock', function(Item $item) {
            return new_number_format($item->previous_stock, 2);
        })
        ->addColumn('created_by', function(Item $item) {
            return optional($item->createdBy)->name ?? "N/A";
        })
        ->addColumn('action', function(Item $item) {
            return make_action_btn([
                '<a href="'.route("edit_item",['item_code' => $item->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="javascript:void(0)" data-id="'.$item->id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->item_datatables();
        }
        return view('MBCorporationHome.item.index');
    }
    public function item_create_from()
    {
        $units = Unit::get(['id', 'name']);
        $categories = Category::get(['id', 'name']);
        $godowns = Godown::get(['id', 'name']);

        return view('MBCorporationHome.item.item_create', compact('categories', 'units', 'godowns'));
    }

    public function store_item(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:items|max:25|min:2',
            'unit_id' => 'required',
            'category_id' => 'required',
            'godown_id' => 'required',
            'previous_stock' => 'required',
        ]);


        try {
            DB::beginTransaction();
            $item_code = "IT" . rand(1111, 9999);
            $item=Item::create([
                'item_code'                     => $item_code,
                'name'                          => $request->name,
                'unit_id'                       => $request->unit_id,
                'how_many_unit'                 => $request->how_many_unit,
                'category_id'                   => $request->category_id,
                'godown_id'                     => $request->godown_id,
                'purchases_price'               => $request->purchases_price??0,
                'sales_price'                   => $request->sales_price??0,
                'previous_stock'                => $request->previous_stock??0,
                'total_previous_stock_value'    => $request->total_previous_stock_value??0,
                'item_description'              => $request->item_description,
            ]);

            if($request->godown_id  &&  $request->previous_stock > 0){
                // StockDetail
                $stock = $request->all();
                $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                $stock['item_id'] = $item->id;
                $stock['purchases_price'] = $request->purchases_price;
                $stock['sale_price'] = $request->sales_price;
                $stock['godown_id'] = $request->godown_id;
                $stock['qty'] = $request->previous_stock;

                $v =StockDetail::create($stock);

                // StockHistory
                $stockHistory = $request->all();
                $stockHistory['in_qty'] = $request->previous_stock;
                $stockHistory['item_id'] =  $item->id;
                $stockHistory['category_id'] = $request->category_id ;
                $stockHistory['average_price'] = $request->purchases_price ;
                $v =$item->stock()->create($stockHistory);

                // ItemCount
                ItemCount::updateOrCreate(['item_id' => $item->id],['stock_qty' => $request->previous_stock ]);


            }
            (new LogActivity)->addToLog('Item Created.');

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }

        return redirect()->to('item_list');
    }

    public function edit_item($id)
    {   $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $item = Item::where('id', $id)->with(['category', 'unit', 'godown'])->first();
        $godowns = Godown::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);
        $categories = Category::get(['id', 'name']);
        return view('MBCorporationHome.item.edit', compact('categories', 'units', 'item', 'godowns'));
    }

    public function update_item(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $item =Item::where('id', $id)->first();
            $itemCount = ItemCount::where('item_id',  $id)->first();
            $oldStock = StockDetail::where('item_id', $item->id)->where('godown_id', $item->godown_id)->first();
            $item->update($request->except('_token'));
            if($oldStock){
                
                if($oldStock->godown_id == $request->godown_id){
                    $oldStock->update(['qty' => $request->previous_stock + $oldStock->qty - $oldStock->qty ]);
                }else if($oldStock->godown_id && $oldStock->godown_id != $request->godown_id){
                    $oldStock->update(['qty' =>  $oldStock->qty - $item->previous_stock  ]);
                }
                if(!$oldStock->godown_id && $request->godown_id){
                    $stock = $request->all();
                    $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                    $stock['item_id'] = $item->id;
                    $stock['godown_id'] = $request->godown_id;
                    $stock['qty'] = $request->previous_stock;
                    StockDetail::create($stock);
                }
                 
                $stockHistory_s = StockHistory::whereIn('stockable_type', ['App\Item'])->where('item_id', $item->id)->first();
                if($stockHistory_s) {
                    // StockHistory
                    $stockHistory['in_qty'] = $request->previous_stock;
                    $stockHistory['godown_id'] = $request->godown_id ;
                    $stockHistory['category_id'] = $request->category_id ;
                    $stockHistory['average_price'] = $request->purchases_price;
                    //$stockHistory['date'] = (new Helper)::get_financial_year_from();
                    $stockHistory['date'] = null;
        
                    $stockHistory_s->update($stockHistory);
                }else {
                    $stockHistory['stockable_id'] = $item->id;
                    $stockHistory['stockable_type'] = "App\Item";
                    $stockHistory['item_id'] = $item->id;
                    $stockHistory['in_qty'] = $request->previous_stock;
                    $stockHistory['godown_id'] = $request->godown_id ;
                    $stockHistory['category_id'] = $request->category_id ;
                    $stockHistory['average_price'] = $request->purchases_price;
                    $stockHistory['total_qty'] = $request->previous_stock;
                    $stockHistory['total_average_price'] = $request->purchases_price * $request->previous_stock;
                    //$stockHistory['date'] = (new Helper)::get_financial_year_from();
                    $stockHistory['date'] = null;
        
                    StockHistory::create($stockHistory);
                }
    
                if($itemCount){
                    $itemCount->update(['stock_qty' => $request->previous_stock + $itemCount->stock_qty -  $item->previous_stock  ]);
                }else{
                    ItemCount::updateOrCreate(['item_id' => $item->id],['stock_qty' => $request->previous_stock]);
                }
            }else {
                $stock = $request->all();
                $stock['st_id'] = "ST" . rand(111111, 999999).'-'.date('y');
                $stock['item_id'] = $item->id;
                $stock['purchases_price'] = $request->purchases_price;
                $stock['sale_price'] = $request->sales_price;
                $stock['godown_id'] = $request->godown_id;
                $stock['qty'] = $request->previous_stock;
                StockDetail::create($stock);
                
                $stockHistory = $request->all();
                $stockHistory['in_qty'] = $request->previous_stock;
                $stockHistory['item_id'] =  $item->id;
                $stockHistory['category_id'] = $request->category_id ;
                $stockHistory['average_price'] = $request->purchases_price ;
                $v =$item->stock()->create($stockHistory);

                // ItemCount
                ItemCount::updateOrCreate(['item_id' => $item->id],['stock_qty' => $request->previous_stock ]);
            }
            (new LogActivity)->addToLog('Item Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }

        return redirect()->to('item_list');

    }

    public function delete_item($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            $item = Item::where('id', $id)->first();
            $itemCount = ItemCount::where('item_id',  $id)->first();
            if($itemCount){
                $itemCount->update(['stock_qty' => 0 ]);
            }
            $stocks = StockDetail::where('item_id', $id)->get();
            foreach( $stocks as $stock){
                $stock->delete();
            }
            $item->stock()->delete();
            $item->delete();
            (new LogActivity)->addToLog('Item Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);


    }

    public function print_all_item()
    {
        $Item = Item::get();
        return view('MBCorporationHome.item.print_all_item', compact('Item'));
    }
}
