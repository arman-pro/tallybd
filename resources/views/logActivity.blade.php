@extends('MBCorporationHome.apps_layout.layout')
@section("title", 'Activity Log')
@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title"><i class="fa fa-database" aria-hidden="true"></i>  Log Activity Deleted Cron Job.</h4>
                </div>
                <div class="card-body">
                    <p>
                        <mark>wget -q -O - http://yourdomain.com/logActivity/delete >/dev/null 2>&1</mark>
                    </p>
                  <a href="{{ url('logActivity/delete') }}" class="btn btn-danger btn-sm"> All Delete</a>
                </div>
            </div>
            <div class="card">
              <div class="card-header bg-success text-light">
                 <h4 class="card-title">User Log Activity</h4>
              </div>
              <div class="card-body">
                <table class="table table-bordered" id="activity_list">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date & Time</th>
                            <th>Subject</th>
                            <th>URL</th>
                            <th>Ip </th>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @if($logs->count())
                        <tbody>
                        @foreach($logs as $key => $log)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $log->updated_at}}</td>
                            <td>{{ $log->subject }}</td>
                            <td class="text-success">{{ $log->url }}</td>
                            <td class="text-warning">{{ $log->ip }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td><a href="{{ url('logActivity/delete',  $log->id) }}" class="btn btn-danger btn-sm">Delete</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    @endif
                </table>
              </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function(){
        $('#activity_list').DataTable({
            columnDefs: [
                { targets: 0, searchable: false, },
                { orderable: false, targets: -1, searchable: false, },
            ],
            "language": {
                "searchPlaceholder": "Searhc Here...",
                "paginate": {
                    "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>',
                },
            },
        });
    });
</script>
@endpush
