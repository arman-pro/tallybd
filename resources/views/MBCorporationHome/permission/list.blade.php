@extends('MBCorporationHome.apps_layout.layout')
@section("title", "User Permission")
@section('admin_content')


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <form method="post" class="right-text-label-form feedback-icon-form" action="{{url('userpermission_update')}}" >
                    @csrf
            <div class="card card-shadow mb-4">
                <div class="card-header bg-success text-light fw-bold">
                    <h4 class="card-title">User Permission</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 control-label" for="username1">Username</label>
                        <div class="col-sm-8">
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
                  
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>All Check</label>
                                <input 
                                    type="checkbox" 
                                    data-toggle="toggle" 
                                    data-size="normal"
                                    id="all_check"
                                />
                            </div>
                            
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    @foreach($permissions->chunk(6) as $permission)
                                    
                                    <tr class="text-center">
                                        @foreach($permission as $per)
                                            <?php
                                                $title = str_replace("_"," ", $per->title);
                                                $haveper = DB::table('userpermission')->where(["user_id"=> $user_id, "permission_id"=> $per->id])->count();
                                            ?>
                                            <th>
                                                <input 
                                                    type="checkbox" name="checkbox[]" 
                                                    data-toggle="toggle"
                                                    class="check_elmnt"
                                                    data-onstyle="success"
                                                    data-offstyle="danger"
                                                    @if($haveper) checked @endif
                                                /><br/>
                                                <b>{{ucfirst($title ?? "N/A")}}</b>
                                            </th>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-lg btn-primary fw-bold text-light" name="signup1" value="Sign up">Change Permission</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#all_check').on('change',function(){
                if($(this).is(":checked")) {
                    $('.check_elmnt').prop('checked', true).change()
                } else {
                    $('.check_elmnt').prop('checked', false).change()
                }
            });
        });
    </script>
@endpush


