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
                                    Create Admin
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="" method="post" class=" right-text-label-form feedback-icon-form" action="{{url('insert-admin')}}" enctype="multipart/form-data">
                                	@csrf
                       

                                
                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="username1">Username</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Username" value="{{old('name')}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="email1">Email</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email"  value="{{old('email')}}"  />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="phone1">Phone</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="phone"  value="{{old('phone')}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="address1">Address</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Ex:Feni"   value="{{old('address')}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="password1">Password</label>
                                        <div class="col-sm-5">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password"   value="{{old('password')}}" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="confirm_password1">Confirm password</label>
                                        <div class="col-sm-5">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password"   value="{{old('confirm_password')}}" />
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 control-label" for="picture1">Picture</label>
                                        <div class="col-sm-5">
                                            <input type="file" class="form-control" id="image" name="image" />
                                        </div>
                                    </div>
                                    <br>
                                     <div class="control-group " id="staticParent" style=''>
              <div style='text-align:center;' class='span12'>
         <label style='background-color:#000; height:30px; color:white; font-size:16px; margin-left:10px; width:95%;'  class='linkname '>
                     <input id="chkbx_all"  onclick="return check_all()" type="checkbox"  />&nbsp; 
                     <span><strong class="text-danger ">Select All</strong></span></label>

              </div>
        @if($id->id == '1')

          @if(count($mainMenu) > 0)
            @foreach($mainMenu as $showMainMenu)
            

             <div class='span11 cheeked'>
             <label style='background-color:#ccc;margin-left:10px; width:95%;  height:30px; '  class='linkname '>
 &nbsp;&nbsp; <input type="checkbox" name="linkID[]"  class="check_elmnt" 
  onclick="return chekMain('{{$showMainMenu->id}}')" id='linkID-{{$showMainMenu->id}}' />
                 {{$showMainMenu->Link_Name}}</label></div>
            @if(count($submenu) > 0)
                @foreach($submenu as $showsubmenu)
                    @if($showMainMenu->id == $showsubmenu->mainmenuId)
            <div class="span3" >  
                   <label style='background-color:#fff;margin-left:10px;  width:95%;   height:30px;'>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="SublinkID[]"  class="check_elmnt2" disabled="disabled"
  id="sublinkID-{{$showMainMenu->id}}" value='{{$showMainMenu->id}}and{{$showsubmenu->id}}'/>
                 {{$showsubmenu->submenuname}}</label></div>
               
                 @endif
              @endforeach
            @endif

          @endforeach
        @endif
      
      @else
        
        @if(count($adminwiseMain) > 0)
          @foreach($adminwiseMain as $showMainMenu)
            <div class='span11 cheeked'>
             <label style='background-color:#ccc;margin-left:10px; width:95%;  height:30px; '  class='linkname '>
 &nbsp;&nbsp; <input type="checkbox" name="linkID[]"  class="check_elmnt" 
  onclick="return chekMain('{{$showMainMenu->id}}')" id='linkID-{{$showMainMenu->id}}' />
                 {{$showMainMenu->Link_Name}}</label></div>

            @if(count($adminwiseSub) > 0)
              @foreach($adminwiseSub as $showsubmenu)
                @if($showMainMenu->id == $showsubmenu->mainmenuId)
            <div class="span3" >  
                   <label style='background-color:#fff;margin-left:10px;  width:95%;   height:30px;'>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="SublinkID[]"  class="check_elmnt2" disabled="disabled"
  id="sublinkID-{{$showMainMenu->id}}" value='{{$showMainMenu->id}}and{{$showsubmenu->id}}'/>
                 {{$showsubmenu->submenuname}}</label></div>
               
                 @endif
              @endforeach
            @endif

            @endforeach
        @endif

      @endif
         

                
      </div>

                                 

                                    <div class="form-group row">
                                        <div class="col-sm-8 ml-auto">
                                            <button type="submit" class="btn btn-info" name="signup1" value="Sign up">Create</button>
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