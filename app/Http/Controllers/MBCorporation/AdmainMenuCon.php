<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use App\User;

use App\linkpiority;
use App\adminmainmenu;
use App\adminsubmenu;


class AdmainMenuCon extends Controller
{

    public function index(){

        $id =   Auth::guard()->user();

        $vale   = linkpiority::where('adminid', '=', $id->id)
        ->get();

        $mainlink = linkpiority::join('adminmainmenu', 'adminmainmenu.id', '=', 'linkpiority.mainlinkid')
        ->select('linkpiority.*','adminmainmenu.*')
        ->groupBy('linkpiority.mainlinkid')
        ->orderBy('adminmainmenu.serialNo', 'ASC')
        ->where('linkpiority.adminid',$id->id)
        ->get();

        $sublink = linkpiority::join('adminsubmenu', 'adminsubmenu.id', '=', 'linkpiority.sublinkid')
        ->select('linkpiority.*','adminsubmenu.*')
        ->orderBy('adminsubmenu.serialno', 'ASC')
        ->where('linkpiority.adminid',$id->id)
        ->get();


        $Adminminlink = adminmainmenu::orderBy('adminmainmenu.serialNo', 'ASC')
        ->get();

        $adminsublink = adminsubmenu::orderBy('adminsubmenu.serialno', 'ASC')

        ->get();


        $mainMenu  = adminmainmenu::orderBy('serialNo', 'asc')
        ->get();


        return  view('MBCorporationHome.developermenu.mainmenu',compact('mainMenu','mainlink','id','sublink','Adminminlink','adminsublink'));
    }





    public function store(Request $request){



        $this->validate($request, [
            'MenuNameEn' => 'required|min:2',
            'serial' => 'required',
            'Route' => 'required',
        ]);



        $insertDate = adminmainmenu::create(
            ['Link_Name' =>  $request->MenuNameEn,
            'serialNo' => $request->serial,
            'routeName' => $request->Route]
        );

        if($insertDate){

            Session::flash('success','Save Success');
        }else{

            Session::flash('error',$insertDate);

        }
        return redirect()->route('MainMenu');



    }


    public function showDate($id){


     $data = adminmainmenu::where('id', '=', $id)->get();
     return view('MBCorporationHome.developermenu.mainmenumodel',compact('data'));
 }

 public function update(Request $request){

    if($request->MenuNameEn != "" && $request->serial != "" && $request->Route != ""){


        $EditData =adminmainmenu::where('id', $request->id)
        ->update(['Link_Name' => $request->MenuNameEn,
            'serialNo' => $request->serial,
            'routeName' => $request->Route]);

        if($EditData){

            Session::flash('success','Edit Success');
        }else{

            Session::flash('error',$EditData);

        }
    }else{
       Session::flash('error','Please Fill up required fields');
   }
   return redirect()->route('MainMenu');


}

public function Dalete($id){

    $obj = adminmainmenu::where('id', '=', $id)->delete();


    if($obj== true){
        return response()->json(['success'=>true,'status'=>'Delete Successfully']);

    }else {

        return response()->json(['error'=>true,'status'=>'Delete Unsuccessfully']);

    }


}


}
