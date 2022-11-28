@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('message'))
                <p class="alert alert-info">{{ Session::get('message') }}</p>
                @endif
                <div class="card">

                    <div class="card-body" style="overflow-x:auto;">

                        <h4 class="card-title"
                            style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All Employees
                            Manage
                            <a href="{{ route('employee.create') }}"><span style="display: block;text-align:right"><button
                                        class="btn btn-md btn-success"> Add +</button></span></a>
                        </h4>

                        <table class="table table-resposive table-bordered" id="example">
                            <thead style="background-color: #566573;text-align: center;">
                                <th style="color: #fff;"># SL</th>
                                <th style="color: #fff;"> Name</th>
                                <th style="color: #fff;"> Mobile</th>
                                <th style="color: #fff;"> Join Date</th>
                                <th style="color: #fff;"> Salary</th>
                                <th style="color: #fff;"> Active/ Deactive</th>

                                <th style="color: #fff;">Action</th>
                            </thead>
                            <tbody>

                                @foreach($employees as $key=>$row)
                                <tr style="text-align: center;">
                                    <td>{{$key+1}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>{{$row->mobile}}</td>
                                    <td>{{date('m-d-y', strtotime($row->joining_date))}}</td>
                                    <td>{{$row->salary}}</td>
                                    <td>@if ($row->status)
                                        Active
                                        @else
                                        Deactive
                                        @endif</td>
                                    <td>
                                        <a href="{{URL::to('/employee/edit/'.$row->id)}}" class="btn btn-sm btn-primary"><i
                                                class="far fa-edit"></i></a>
                                        {{-- <a href="{{URL::to('/employee/edit/'.$row->id)}}"
                                            class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a> --}}
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
