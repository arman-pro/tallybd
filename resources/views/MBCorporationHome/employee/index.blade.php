@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'All Employee List')
@section('admin_content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if(Session::has('message'))
            <p class="alert alert-info">{{ Session::get('message') }}</p>
            @endif
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">All Employe List</h4>
                </div>
                <div class="card-body overflow-auto">
                    <div class="mb-3">
                        <a href="{{ route('employee.create') }}" class="btn btn-md btn-success">Add New</a>
                    </div>
                    <table class="table table-bordered" id="employee_list">
                        <thead class="bg-light text-dark">
                            <th># SL</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Join Date</th>
                            <th>Salary</th>
                            <th>Active/ Deactive</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($employees as $key=>$row)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$row->name}}</td>
                                <td>{{$row->mobile}}</td>
                                <td>{{date('m-d-y', strtotime($row->joining_date))}}</td>
                                <td>{{$row->salary}}</td>
                                <td>
                                    @if ($row->status)
                                        Active
                                    @else
                                        Deactive
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <b>Action</b>
                                        </button>
                                        <div class="dropdown-menu" style="margin: 0px;">
                                            <a href="{{URL::to('/employee/edit/'.$row->id)}}" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>
                                            <a href="{{URL::to('/employee/status/'.$row->id)}}" onclick="alert('Do You want to change status?')" class="dropdown-item">
                                                <i class="fa fa-check"></i> Status
                                            </a>
                                            <a href="{{URL::to('/employee/delete/'.$row->id)}}" onclick="alert('Do You want to delete?')" class="dropdown-item">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
        $('#employee_list').DataTable({
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
