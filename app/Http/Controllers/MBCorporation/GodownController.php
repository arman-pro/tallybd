<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Item;
use App\Godown;
use App\Helpers\LogActivity;
use App\Stockdetail;
use Illuminate\Support\Facades\DB;
use Session;
use App\Helpers\Helper;

class GodownController extends Controller
{

    public function index()
    {
        $mes = "";
        $godowns =  Godown::paginate(20);
        return view('MBCorporationHome.godown.index', compact('godowns', 'mes'));
    }
    public function godown_create_from ()
    {
        return view('MBCorporationHome.godown.create');
    }

    public function store_godown_create_from(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:godowns|max:25|min:2',
        ]);
        
        $godown_id = 'GID-' . rand(111, 999);
        Godown::insert([
            'godown_id' => $godown_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $mes = "Successfully Add Godown";
        (new LogActivity)->addToLog('Godown Created.');

        $godowns  =  Godown::get();
        return view('MBCorporationHome.godown.index', compact('godowns', 'mes'));
    }


    public function edit_godown($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $godown  =  Godown::whereId($id)->first();
        return view('MBCorporationHome.godown.edit', compact('godown'));
    }

    public function update_godown(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:25|min:2',
        ]);

        Godown::where('id', $id)->Update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $godowns =  Godown::paginate(20);
        (new LogActivity)->addToLog('Godown Updated.');

        $mes = "Successfully Update";
        return view('MBCorporationHome.godown.index', compact('godowns', 'mes'));
    }

    public function delete_godown($id)
    {

        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            Godown::whereId($id)->delete();
            (new LogActivity)->addToLog('Godown Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

    }
}
