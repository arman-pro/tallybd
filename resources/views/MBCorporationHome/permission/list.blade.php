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
                                    User Permission
                                </div>
                            </div>
                            <div class="card-body">
                              


                              
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="username1">Username</label>
                                    <div class="col-sm-5">
                                        <ul class="navbar-nav float-start me-auto">
                                          <li class="nav-item dropdown">
                                              <a class=" nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 18px;font-weight: 600;color: #000">
                                                  {{$user_name}}
                                              </a>
                                              <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                                                @foreach ($users as $row)                              
                                                  <a class="dropdown-item" href="{{url('/user_permission')}}/{{ $row->id }}"><i
                                                          class="fa fa-power-off me-1 ms-1"></i> {{ $row->name??' ' }}</a>
                                                @endforeach  
                                              </ul>
                                          </li>
                                        </ul>
                                        
                                    </div>
                                </div>
                              <form method="post" class="right-text-label-form feedback-icon-form" action="{{url('userpermission_update')}}" >
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="username1">Permission List</label>
                                    <div class="col-sm-5">
                                      <label class='linkname '>
                                        <input id="chkbx_all"  onclick="return check_all()" type="checkbox"  />&nbsp; 
                                        <span><strong class="text-danger ">Select All</strong></span>
                                      </label>
                                       
                                        <table>
                                          @foreach ($permissions as $prem)
                                          @php
                                          $title = str_replace("_"," ", $prem->title);
                                          $haveper = DB::table('userpermission')->where(["user_id"=> $user_id, "permission_id"=> $prem->id])->count();

                                          @endphp
                                          <tr>
                                            <td>
                                              <h4 style="text-transform: uppercase;">{{ $title??' ' }}</h4>
                                            </td>
                                            <td>
                                               
                                              <input type="checkbox" name="checkbox[]" value="{{ $prem->id}}" @if(!empty($haveper)) checked @endif class="check_elmnt">
                                            </td>
                                          </tr>
                                          @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-info" name="signup1" value="Sign up">Set Permission</button>
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
         
        $('input.check_elmnt').prop('checked', true);
         
      }else{
         
        $('input.check_elmnt').prop('checked', false);
         
        }
    } 
    

     

</script>
@endsection