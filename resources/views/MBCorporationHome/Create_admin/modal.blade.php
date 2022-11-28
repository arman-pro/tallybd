@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')



<div class="main-content">
    <div class="container-fluid">

<br>
<br>
<br>
<br>
    @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-shadow mb-4">
                            <div class="card-header">
                                <div class="card-title">
                                    update Admin
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="" method="post" class=" right-text-label-form feedback-icon-form" action="{{url('update-admin')}}/{{$data->id}}" enctype="multipart/form-data">
                                    @csrf
                       

                                
                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="username1">Username</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Username" value="{{$data->name}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="email1">Email</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{$data->email}}" required="" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="phone1">Phone</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="phone" value="{{$data->phone}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="address1">Address</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Ex:Feni" value="{{$data->address}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="password1">Password</label>
                                        <div class="col-sm-5">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="confirm_password1">Confirm password</label>
                                        <div class="col-sm-5">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password"/>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="picture1">Picture</label>
                                        <div class="col-sm-5">
                                            <input type="file" class="form-control" id="image" name="image" /><br>
                                            <img src="{{asset('/AdminImage')}}/{{$data->image}}" style="width: 100px;height: 100px">
                                        </div>
                                    </div>
                                                            <br>
                                     <div class="control-group " id="staticParent" style=''>
              <div style='text-align:center;' class='span12'>
         <label style='background-color:#000; height:30px; color:white; font-size:16px; margin-left:10px; width:95%;'  class='linkname '>
                     <input id="chkbx_all"  onclick="return check_all()" type="checkbox"  />&nbsp; 
                     <span><strong class="text-danger ">Select All</strong></span></label>

   
             @if(count($mainMenu) > 0)
             @foreach($mainMenu as $showMainMenu)
             @php
             $linkpirs = DB::table('linkpiority')->where('linkpiority.adminid', $data->id)->where('linkpiority.mainlinkid', $showMainMenu->id)->count();
             @endphp
          
             
                
             <div class='span11 cheeked'>
             <label style='background-color:#ccc;margin-left:10px;width:95%;height:30px;'  class='linkname '>
 &nbsp;&nbsp; <input type="checkbox" name="linkID[]"  @if(!empty($linkpirs)) checked @endif  class="check_elmnt" 
  onclick="return chekMain('{{$showMainMenu->id}}')" id='linkID-{{$showMainMenu->id}}' />
                 {{$showMainMenu->Link_Name}}</label></div>


          
              
            @if(count($submenu) > 0)
            @foreach($submenu as $showsubmenu)
            @if($showMainMenu->id == $showsubmenu->mainmenuId)
            @php
            $linkpirss = DB::table('linkpiority')->where('linkpiority.adminid', $id)->where('linkpiority.sublinkid', $showsubmenu->id)->count();
            @endphp
            <div class="span3" >  
                   <label style='background-color:#fff;margin-left:10px;  width:95%;   height:30px;'>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="SublinkID[]" @if(!empty($linkpirss)) checked @endif  class="check_elmnt2" 
  id="sublinkID-{{$showMainMenu->id}}" value='{{$showMainMenu->id}}and{{$showsubmenu->id}}'/>
                 {{$showsubmenu->submenuname}}</label></div>
               
                 
            @endif
            @endforeach
            @endif
                  
                  
              
           
            @endforeach
            @endif
      

              </div>

           
                
      </div>
                                 

                                    <div class="form-group row">
                                        <div class="col-sm-8 ml-auto">
                                            <button type="submit" class="btn btn-info" name="signup1" value="Sign up">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>







    </div>
</div>









                                
<script type="text/javascript">
      function check_all()
      {
      
      if($('#chkbx_all').is(':checked')){
        $('input.check_elmnt2').prop('disabled', false);
        $('input.check_elmnt').prop('checked', true);
        $('input.check_elmnt2').prop('checked', true);
      }else{
        $('input.check_elmnt2').prop('disabled', true);
        $('input.check_elmnt').prop('checked', false);
        $('input.check_elmnt2').prop('checked', false);
        }
    } 
    

    function chekMain(getID){
       
          if($('#linkID-'+getID).is(':checked')){
              
            $("input#sublinkID-"+getID).attr('disabled', false);
            $("input#sublinkID-"+getID).attr('checked', true);
          
          }else{
            $("input#sublinkID-"+getID).attr('disabled', true);
            $("input#sublinkID-"+getID).attr('checked', false);
          
          }
      
        }


</script>
@endsection