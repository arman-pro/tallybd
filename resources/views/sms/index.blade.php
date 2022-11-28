@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
       <div class="row">
           <div class="col-sm-12">
               <a href="{{route('sms.create')}}" class="btn btn-dark">Create</a>
           </div>
       </div>
       <br/>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">SMS Provider List</h4>
                    </div>
                    <div class='card-body'>
                        @if(session()->has('message'))
                            <div class="alert alert-success">{{session()->get('message')}}</div>
                        @endif
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Provider</th>
                                    <th>URL</th>
                                    <th>Total SMS</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($sms_s->isNotEmpty())
                            @foreach($sms_s as $_sms)
                                <tr>
                                    <td>{{$_sms->provider ?? "N/A"}}</td>
                                    <td>{{$_sms->provider_url ?? "N/A"}}</td>
                                    <td>
                                        {{$_sms->get_balance($_sms->sms_check_url) ?? 0}}
                                    </td>
                                    <td>
                                        @if($_sms->is_active)
                                            <span class="bg-success p-1 rounded text-light">Active</span>
                                        @else
                                            <span class="bg-danger p-1 rounded text-light">Deactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('sms.active', ['id'=> $_sms->id])}}" class="btn btn-sm btn-dark">Active</a>
                                        <a href="{{route('sms.edit', ['id'=> $_sms->id])}}" class="btn btn-sm btn-outline-dark">Edit</a>
                                        <a onclick="return confirm('Are your sure to delete');" href="{{route('sms.destroy', ['id'=> $_sms->id])}}" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            @endif
                            
                            @empty($sms_s)
                                <tr>
                                    <td colspan="5">No Data Found</td>
                                </tr>
                            @endempty
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
         {{--   <div class="col-md-6">

            <div class="card">
                <div class="card-header"  style="font-weight: 800;  border-bottom: 2px solid #eee; background-color: #00bcd4;color:white;text-align:center">SMS Balance</div>
                <div class="card-body">
                    <strong>{{$smsBalance}} Tk</strong>
                </div>
            </div>
            <div class="card">
                <div class="card-header"  style="font-weight: 800;  border-bottom: 2px solid #eee; background-color: #94cd26;color:white;text-align:center">SMS API Manage</div>
                <div class="card-body">
                    <form action="{{ route('sms.setting') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="apiKey">API Key</label>
                            <input type="text" name="apiKey"  class="form-control" id="apiKey"placeholder="......." value="{{ $sms->apiKey??' '}}" required>
                        </div>
                        <div class="form-group">
                            <label for="senderId">Sender ID</label>
                            <input type="text" name="senderId"  class="form-control" id="senderId"placeholder="......." value="{{ $sms->senderId??" " }}" required>
                        </div>
                        <div class="form-group">
                            <label for="smsType">SMS Type</label>
                            <input type="text" name="smsType" class="form-control" id="smsType"placeholder="......." value="{{ $sms->smsType??' ' }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary b-block text-right">Submit</button>
                    </form>
                </div>
              </div>
            </div> --}}
            
            (এসএমএস ডেলিভারী নেটওয়াক এর কারণে ৩০ সেকেন্ড পযন্ত দেরী হতে পারে।

            <div class="col-md-8 col-sm-12 m-auto">
              <div class="card">
                  <div class="card-header"  style=" font-weight: 800;  border-bottom: 2px solid #eee; background-color: #DC7633;color:white;text-align:center">Custom SMS Send</div>
                {{-- <h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633;">Send Sms</h4><br> --}}

                    <div class="card-body">
                        <div style="text-align: center;">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        
                        @if($message = Session::get('sms_body'))
                        <div class="alert alert-success alert-block">
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                            <form action="{{ route('sms.send') }}" method="POST">
                            @csrf

                                <input class="form-control" type="text" name="number" placeholder="017*********">
                                <label for="">SMS Body</label>
                                <textarea  class="form-control" name="smsBody" id="" cols="30" rows="10"></textarea><br/>
                                <button class="btn btn-success" style="color: #fff;">Send</button>
                            </form>

                        </div>
                    </div>
              </div>
            </div>
        <div>

</div>
@endsection
