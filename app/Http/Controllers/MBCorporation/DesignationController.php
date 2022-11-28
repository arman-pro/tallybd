<?php

namespace App\Http\Controllers\MBCorporation;

use App\Designation;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Helpers\Helper;

class DesignationController extends Controller
{
    public function index()
    {
        $mes = "";
        $designations =  Designation::get();
        return view('MBCorporationHome.designation.index', compact('designations', 'mes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:designations|max:25|min:2',
        ]);

        Designation::create([
            'name' => $request->name,
        ]);
        (new LogActivity)->addToLog('Designation Created.');

        $mes = "Successfully Add Designation";
        $designations =  Designation::get();
        return view('MBCorporationHome.designation.index', compact('designations', 'mes'));
    }
    public function edit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $mes = "";
        $designations =  Designation::get();
        $oneDesignation =  Designation::where('id', $id)->get();
        return view('MBCorporationHome.designation.edit', compact('designations', 'oneDesignation', 'mes'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:25|min:2',
        ]);
        Designation::where('id', $id)->update([
            'name' => $request->name,

        ]);
        (new LogActivity)->addToLog('Designation Updated.');

        $mes = "Successfully Update";
        $designations =  Designation::get();
        return view('MBCorporationHome.designation.index', compact('designations', 'mes'));
    }

    public function delete($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $mes = "Successfully Deleted";
        Designation::where('id', $id)->delete();
        (new LogActivity)->addToLog('Designation Deleted.');

        $designations =  Designation::get();
        return view('MBCorporationHome.designation.index', compact('designations', 'mes'));
    }
}
