<?php

namespace App\Http\Controllers\MBCorporation;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Unit;
use Session;
use App\Helpers\Helper;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('update') && $request->input('update')) {
            $item = Unit::where('id', $request->id)->first();
            $mes ="";
    	    $Unit =  Unit::get();
    	    
    	    return view('MBCorporationHome.unit.index', compact('Unit','mes', 'item'));
        }
    	$mes ="";
    	$Unit =  Unit::get();
        return view('MBCorporationHome.unit.index', compact('Unit','mes'));
    }

    public function store_unit(Request $request)
    {
       $validatedData = $request->validate([
            'name' => 'required|unique:units|max:25|min:2',
            ]);
        Unit::insert([
         	'name'=>$request->name,
         ]);
        $mes = "Successfully Add Unit";
        (new LogActivity)->addToLog('Unit Created.');

    	$Unit =  Unit::get();
         return view('MBCorporationHome.unit.index', compact('Unit','mes'));

     }
    public function edit_unit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
    	$mes = "";
        $Unit =  Unit::get();
        $oneUnit =  Unit::where('id',$id)->get();
        return view('MBCorporationHome.unit.edit_unit', compact('Unit','oneUnit','mes'));
    }

    public function update_unit(Request $request,$id)
    {
       $validatedData = $request->validate([
            'name' => 'required|max:25|min:2',
            ]);
         Unit::where('id',$id)->Update([

         	'name'=> $request->name,

         ]);
         
        (new LogActivity)->addToLog('Unit Updated.');

    	$mes = "Successfully Update";
        $Unit =  Unit::get();
        return redirect()->route('unit_list')->with('message', 'update successfull');
        // return view('MBCorporationHome.unit.index', compact('Unit','mes'));
    }

    public function delete_unit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
    	$mes = "Successfully Deleted";
    	Unit::where('id',$id)->delete();
    	$Unit =  Unit::get();
         return view('MBCorporationHome.unit.index', compact('Unit','mes'));
    }

}
