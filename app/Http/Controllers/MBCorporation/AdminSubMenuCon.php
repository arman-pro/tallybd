<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use App\linkpiority;
use App\adminmainmenu;
use App\adminsubmenu;
use App\Helpers\Helper;

class AdminSubMenuCon extends Controller
{

    public function index(){

 $id =   Auth::guard()->user();

         $vale   = DB::table('linkpiority')
                ->where('adminid', '=', $id->id)
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


        $users = adminsubmenu::join('adminmainmenu', 'adminmainmenu.id', '=', 'adminsubmenu.mainmenuId')
            ->select('adminsubmenu.*', 'adminmainmenu.Link_Name')
            ->orderBy('adminsubmenu.serialno', 'ASC')
            ->get();


        $mainmenu= adminmainmenu::orderBy('serialNo', 'ASC')->get();
        return view('MBCorporationHome.developermenu.SubMenu',compact('mainmenu','users','mainlink','id','sublink','Adminminlink','adminsublink'));
    }

    public function store(Request $request){



            $this->validate($request, [
                'mainMenuName' => 'required',
                'SubMenuEn' => 'required|min:2',
                'serial' => 'required',
                'Route' => 'required',
            ]);

            if($request->mainMenuName != "Select One"){

            $insertDate = adminsubmenu::create(
                            ['mainmenuId' =>  $request->mainMenuName,
                            'submenuname' => $request->SubMenuEn,
                             'serialno' => $request->serial,
                            'routeName' => $request->Route]
                        );

                if($insertDate){

                    Session::flash('success','Save Success');
                }else{

                    Session::flash('error',$insertDate);

                }
            }else{
                Session::flash('error','Save Unsuccess');
            }
                return redirect()->route('SubMenu');



    }

    public function showDate($id){

            $data = adminsubmenu::join('adminmainmenu', 'adminmainmenu.id', '=', 'adminsubmenu.mainmenuId')
            ->select('adminsubmenu.*', 'adminmainmenu.Link_Name as mainmenu')
            ->where('adminsubmenu.id', '=', $id)
            ->get();
            $mainmenu= adminmainmenu::orderBy('serialNo', 'ASC')->get();
            return view('MBCorporationHome.developermenu.submenumodel',compact('data','mainmenu'));
    }

 public function update(Request $request){

    if($request->mainMenuName != "" && $request->serial != "" && $request->Route != "" && $request->submenu != ""){


                $EditData =adminsubmenu::where('id', $request->id)
                            ->update(['mainmenuId' => $request->mainMenuName,
                                'serialno' => $request->serial,
                                'routeName' => $request->Route,
                                'submenuname' => $request->submenu]);

                if($EditData){

                    Session::flash('success','Edit Success');
                }else{

                    Session::flash('error',$EditData);

                }
            }else{
                 Session::flash('error','Please Fill up required fields');
            }
                return redirect()->route('SubMenu');


    }

     public function Dalete($id){
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
                $obj = adminsubmenu::where('id', '=', $id)->delete();


        if($obj== true){
                    return response()->json(['success'=>true,'status'=>'Delete Successfully']);

            }else {

                    return response()->json(['error'=>true,'status'=>'Delete Unsuccessfully']);

            }


    }




}
