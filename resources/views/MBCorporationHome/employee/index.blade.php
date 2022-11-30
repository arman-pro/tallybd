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
                    <table class="table table-bordered" id="example">
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
                                    <a href="{{URL::to('/employee/edit/'.$row->id)}}" class="btn btn-sm btn-primary"><i
                                            class="far fa-edit"></i></a>
                                    <a href="{{URL::to('/employee/status/'.$row->id)}}"
                                        onclick="alert('Do You want to change status?')"
                                        class="btn btn-sm btn-warning"><i class="fa fa-check"></i></a>
                                    <a href="{{URL::to('/employee/delete/'.$row->id)}}"
                                        onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i></a>
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
