<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use Session;
use App\Helpers\Helper;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('update') && $request->update) {
            $mes = "";
            $category =  Category::get();
            $item = Category::findOrFail($request->id);
            return view('MBCorporationHome.category.index', compact('category','mes', 'item'));
        }
    	$mes = "";
        $category =  Category::get();
        return view('MBCorporationHome.category.index', compact('category','mes'));
    }

    public function store_category(Request $request)
    {
       $validatedData = $request->validate([
            'name' => 'required|unique:categories|max:25|min:2',
            ]);

         Category::insert([
         	'name'=>$request->name,
         ]);
          $mes = "Successfully Add category";
    	 $category =  Category::get();
         (new LogActivity)->addToLog('Category Created.');

         return view('MBCorporationHome.category.index', compact('category','mes'));
    }


    public function edit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
    	$mes = "";
        $category =  Category::get();
        $onecategory =  Category::where('id',$id)->get();
         return view('MBCorporationHome.category.edit', compact('category','onecategory','mes'));
    }

    public function update_category(Request $request,$id)
    {
       $validatedData = $request->validate([
            'name' => 'required|max:25|min:2',
            ]);
         Category::where('id',$id)->Update([

         	'name'=>$request->name,

         ]);
         (new LogActivity)->addToLog('Category Updated.');

    	 $mes = "Successfully Update";
         $category =  Category::get();
         return redirect()->route('category')->with('mes', 'Category Updated');
    }

    public function delete_category($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            Category::where('id',$id)->delete();
            (new LogActivity)->addToLog('Godown Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
    	// $mes = "Successfully Deleted";
    	// try {
        //     Category::where('id',$id)->delete();
        //     (new LogActivity)->addToLog('Category Deleted.');
        // } catch (\Throwable $th) {
        //     $mes = "This Data used another Table";
        // }

    	// $category =  Category::get();
        // return view('MBCorporationHome.category.index', compact('category',))->with('mes', 'This Data already used another table.');
    }
}
