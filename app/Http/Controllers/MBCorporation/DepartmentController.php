<?php

namespace App\Http\Controllers\MBCorporation;

use App\Department;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Helpers\Helper;

class DepartmentController extends Controller
{
    public function index()
    {
    	$mes ="";
    	$departments =  Department::get();
        return view('MBCorporationHome.department.index', compact('departments','mes'));
    }

    public function store(Request $request)
    {
       $validatedData = $request->validate([
            'name' => 'required|unique:departments|max:25|min:2',
            ]);

         Department::create([
         	'name'=>$request->name,
         ]);
         (new LogActivity)->addToLog('Department Created.');

        $mes = "Successfully Add Department";
    	$departments =  Department::get();
         return view('MBCorporationHome.department.index', compact('departments','mes'));

     }
    public function edit($id)
    {  $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
    	$mes = "";
        $departments =  Department::get();
        $oneDepartment =  Department::where('id',$id)->get();
        return view('MBCorporationHome.department.edit', compact('departments','oneDepartment','mes'));
    }

    public function update(Request $request,$id)
    {
       $validatedData = $request->validate([
            'name' => 'required|max:25|min:2',
            ]);
         Department::where('id',$id)->update([
         	'name'=>$request->name,

         ]);
         (new LogActivity)->addToLog('Department Updated.');

    	 $mes = "Successfully Update";
        $departments =  Department::get();
         return view('MBCorporationHome.department.index', compact('departments','mes'));
    }

    public function delete($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
    	$mes = "Successfully Deleted";
    	Department::where('id',$id)->delete();
        (new LogActivity)->addToLog('Department Deleted.');

    	$departments =  Department::get();
         return view('MBCorporationHome.department.index', compact('departments','mes'));
    }

}
