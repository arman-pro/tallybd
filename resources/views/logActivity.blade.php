@extends('MBCorporationHome.apps_layout.layout')
@push('css')

@section('admin_content')
<div style="background: #fff;">

    <div class="row">

        <div class="container table-responsive py-5">
            <div class="card">
                <div class="card-header  text-center">
                    <i class="fa fa-database" aria-hidden="true"></i>  Log Activity Deleted Cron Job.
                </div>
                <div class="card-body">
                    <mark>
                        wget -q -O - http://yourdomain.com/logActivity/delete >/dev/null 2>&1
                    </mark>
                  <br>
                  <a href="{{ url('logActivity/delete') }}" class="btn btn-danger btn-sm"> All Delete</a>
                </div>
            </div>
          <div class="card">
              <div class="card-header text-center">
                 <h4> User Log Activity</h4>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Date & Time</th>
                        <th>Subject</th>
                        <th>URL</th>
                        <th>Ip </th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                    @if($logs->count())
                        @foreach($logs as $key => $log)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $log->updated_at}}</td>
                            <td>{{ $log->subject }}</td>
                            <td class="text-success">{{ $log->url }}</td>
                            <!--{{--<td><label class="label label-info">{{ $log->method }}</label></td> ---}}-->
                            <td class="text-warning">{{ $log->ip }}</td>
                            <!--{{-- <td class="text-danger">{{ $log->agent }}</td> --}}-->
                            <td>{{ $log->user->name }}</td>
                            <td><a href="{{ url('logActivity/delete',  $log->id) }}" class="btn btn-danger btn-sm">Delete</a></td>
                        </tr>
                        @endforeach
                    @endif
                </table>
              </div>

          </div>
        </div>



    </div>
</div>

@endsection
