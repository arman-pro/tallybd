<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\linkpiority;
use App\adminmainmenu;
use App\adminsubmenu;
use App\Helpers\LogActivity;
use Auth;
use Hash;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Session;
use Validator;

class AdminController extends Controller
{

    public function login()
    {
        return view('Login.login');
    }



    public function index()
    {
        $id =   Auth::guard()->user();


        $mainlink = linkpiority::join('adminmainmenu', 'adminmainmenu.id', '=', 'linkpiority.mainlinkid')
            ->groupBy('linkpiority.mainlinkid')
            ->select('linkpiority.*', 'adminmainmenu.*')
            ->orderBy('adminmainmenu.serialNo', 'ASC')
            ->where('linkpiority.adminid', $id->id)
            ->get();

        $sublink = linkpiority::join('adminsubmenu', 'adminsubmenu.id', '=', 'linkpiority.sublinkid')
            ->select('linkpiority.*', 'adminsubmenu.*')
            ->orderBy('adminsubmenu.serialno', 'ASC')
            ->where('linkpiority.adminid', $id->id)
            ->get();


        $Adminminlink = adminmainmenu::orderBy('adminmainmenu.serialNo', 'ASC')
            ->get();

        $adminsublink = adminsubmenu::orderBy('adminsubmenu.serialno', 'ASC')

            ->get();


        $mainMenu  = adminmainmenu::orderBy('serialNo', 'asc')
            ->get();
        $submenu = adminsubmenu::orderBy('serialno', 'ASC')->get();

        $adminwiseMain = linkpiority::join('adminmainmenu', 'linkpiority.mainlinkid', '=', 'adminmainmenu.id')
            ->groupBy('linkpiority.mainlinkid')
            ->where('linkpiority.adminid', $id->id)
            ->get();

        $adminwiseSub = linkpiority::join('adminsubmenu', 'linkpiority.sublinkid', '=', 'adminsubmenu.id')
            ->groupBy('linkpiority.sublinkid')
            ->where('linkpiority.adminid', $id->id)
            ->get();


        return view('MBCorporationHome.Create_admin.index', compact('mainMenu', 'submenu', 'mainlink', 'id', 'sublink', 'Adminminlink', 'adminsublink', 'adminwiseMain', 'adminwiseSub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|unique:users|max:100',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'min:4',
            'confirm_password' => 'required_with:password|same:password|min:4'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('image');
        if (isset($file)) {
            $path = rand() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/AdminImage/'), $path);
        } else {
            $path = '';
        }

        $data = array(
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'image' => $path,
        );

        $insert = User::create($data);

        if ($insert) {



            if (count($request->SublinkID) > 0) {

                for ($i = 0; $i < count($request->SublinkID); $i++) {

                    $expolaid = explode('and', $request->SublinkID[$i]);
                    $fffff = linkpiority::insert(
                        [
                            'adminid' => User::all()->last()->id,
                            'mainlinkid' => $expolaid[0],
                            'sublinkid' => $expolaid[1]
                        ]
                    );
                }
            }
            (new LogActivity)->addToLog('User Created.');
            $notification = array(
                'messege'   => 'Registration Successfull',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data = User::all();
        return view('MBCorporationHome.Create_admin.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $file = $request->file('image');
        if (isset($file)) {
            $path = rand() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/AdminImage/'), $path);
        } else {
            $datas = User::find($id);
            $path = $datas->image;
        }

        if ($request->password == "") {
            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $path,
                'status' => '1',
            );
        } else {

            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => FacadesHash::make($request->password),
                'image' => $path,
                'status' => '1',
            );
        }


        $update = User::find($id)->update($data);


        if ($update) {
            if (!empty($request->SublinkID)) {

                $deleteData = linkpiority::where('adminid', '=', $request->id)->delete();
                for ($i = 0; $i < count($request->SublinkID); $i++) {
                    $expolaid = explode('and', $request->SublinkID[$i]);
                    $search = linkpiority::where('adminid', $request->id)->where('mainlinkid', $expolaid[0])->where('sublinkid', $expolaid[1])->first();
                    if ($search) {
                    } else {
                        $fffff = linkpiority::insert(
                            [
                                'adminid' => $request->id,
                                'mainlinkid' => $expolaid[0],
                                'sublinkid' => $expolaid[1]
                            ]
                        );
                    }
                }
            }
        (new LogActivity)->addToLog('User Upfated.');

            $notification = array(
                'messege'   => 'Update Successfull',
                'alert-type' => 'info'
            );

            return redirect()->back()->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $obj = linkpiority::where('adminid', '=', $request->id)->delete();
        $data = User::find($request->id);

        $delete = User::find($request->id)->delete();
        (new LogActivity)->addToLog('User Deleted.');

        $path = base_path() . '/public/AdminImage/' . $data->image;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function LoginAdmin(Request $request)
    {

        $creadintial = ['email' => $request->email, 'password' => $request->password];
        if (Auth()->attempt($creadintial)) {

            if (Auth()->user()->status === '0') {
                Auth()->logout();

                $notification = array(
                    'messege'   => 'Your Account Access Pending!',
                    'alert-type' => 'warning'
                );

                return redirect()->back()->with($notification);
            } else {

                $notification = array(

                    'messege'   => 'Login Successfull',
                    'alert-type' => 'success'
                );
                (new LogActivity)->addToLog('User Login');
                return redirect('/')->with($notification);
            }
        } else {
            $notification = array(
                'messege'   => 'Password and E-mail Does not match!',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }

    public function Adminlogout()
    {
        Auth()->logout();

        $notification = array(
            'messege'   => 'Logout Successfull!',
            'alert-type' => 'info'
        );
        (new LogActivity)->addToLog('User Logout');
        return redirect('/')->with($notification);
    }

    public function inactivestatusadmin(Request $request)
    {
        $inac = User::where('id', $request->id)
            ->update(['status' => '0']);
    }
    public function activestatusadmin(Request $request)
    {
        $inac = User::where('id', $request->id)
            ->update(['status' => '1']);
    }

    public function editadminModal($id)
    {



        $mainlink = linkpiority::join('adminmainmenu', 'adminmainmenu.id', '=', 'linkpiority.mainlinkid')
            ->select('linkpiority.*', 'adminmainmenu.*')
            ->groupBy('linkpiority.mainlinkid')
            ->orderBy('adminmainmenu.serialNo', 'ASC')
            ->where('linkpiority.adminid', $id)
            ->get();

        $sublink = linkpiority::join('adminsubmenu', 'adminsubmenu.id', '=', 'linkpiority.sublinkid')
            ->select('linkpiority.*', 'adminsubmenu.*')
            ->orderBy('adminsubmenu.serialno', 'ASC')
            ->where('linkpiority.adminid', $id)
            ->get();


        $Adminminlink = adminmainmenu::orderBy('adminmainmenu.serialNo', 'ASC')
            ->get();

        $adminsublink = adminsubmenu::orderBy('adminsubmenu.serialno', 'ASC')

            ->get();


        $mainMenu  = adminmainmenu::orderBy('serialNo', 'asc')
            ->get();
        $submenu = adminsubmenu::orderBy('serialno', 'ASC')->get();

        $adminwiseMain = linkpiority::join('adminmainmenu', 'linkpiority.mainlinkid', '=', 'adminmainmenu.id')
            ->groupBy('linkpiority.mainlinkid')
            ->where('linkpiority.adminid', $id)
            ->get();

        $adminwiseSub = linkpiority::join('adminsubmenu', 'linkpiority.sublinkid', '=', 'adminsubmenu.id')
            ->groupBy('linkpiority.sublinkid')
            ->where('linkpiority.adminid', $id)
            ->get();

        $data = User::findOrFail($id);

        return view('MBCorporationHome.Create_admin.modal', compact('mainMenu', 'submenu', 'mainlink', 'id', 'sublink', 'Adminminlink', 'adminsublink', 'adminwiseMain', 'adminwiseSub', 'data'));
    }
    
    public function user_permission($user_id="")
    {
        $user =   Auth::guard()->user();
        $user_id = $user_id>0?$user_id:$user->id;
        $permissions = DB::table('permissions')->get();
        $users = User::get();
        $userinfo = User::find($user_id);
        $user_name = $userinfo->name;
        return view('MBCorporationHome.permission.list', compact('permissions', 'users', 'user_id', 'user_name'));
    }



    public function user_permissionUpdate(Request $request)
    {
        $user =   Auth::guard()->user();
        if (!empty($request->checkbox)) {
            DB::table('userpermission')->where('user_id',  $request->user_id)->delete();
            foreach ($request->checkbox as $key => $per_id) {
                $data = array(
                    'permission_id' => $per_id,
                    'status' => 1,
                    'user_id' => $request->user_id,
                    'created_by' => $user->id, 
                );
                DB::table('userpermission')->insert($data);
            }
        } 
        return redirect()->back();
    }
}
