<?php

namespace App\Http\Controllers\MBCorporation;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Shift;
use Illuminate\Http\Request;
use Session;
use App\Helpers\Helper;

class ShiftController extends Controller
{
    public function index()
    {
        $mes = "";
        $shifts =  Shift::get();
        return view('MBCorporationHome.shift.index', compact('shifts', 'mes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:shifts|max:25|min:2',
        ]);

        Shift::create([
            'name' => $request->name,
        ]);

        $mes = "Successfully Add Shift";
        (new LogActivity)->addToLog('Shift Created .');

        $shifts =  Shift::get();
        return view('MBCorporationHome.shift.index', compact('shifts', 'mes'));
    }
    public function edit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $mes = "";
        $shifts =  Shift::get();
        $oneDesignation =  Shift::where('id', $id)->get();
        return view('MBCorporationHome.shift.edit', compact('shifts', 'oneDesignation', 'mes'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:25|min:2',
        ]);
        Shift::where('id', $id)->update([
            'name' => $request->name,

        ]);
        (new LogActivity)->addToLog('Shift Updated .');

        $mes = "Successfully Update";
        $shifts =  Shift::get();
        return view('MBCorporationHome.shift.index', compact('shifts', 'mes'));
    }

    public function delete($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $mes = "Successfully Deleted";
        Shift::where('id', $id)->delete();
        (new LogActivity)->addToLog('Shift Deleted .');
        $shifts =  Shift::get();
        return view('MBCorporationHome.shift.index', compact('shifts', 'mes'));
    }
}
