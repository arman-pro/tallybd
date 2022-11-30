@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Designation List')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card overflow-auto">
            <div class="card-header bg-success">
                <h4 class="card-title">All Designations Manage</h4>
            </div>
            <div class="card-body">
                <table class="table table-resposive table-bordered" id="example">
                    <thead class="bg-light text-dark">
                        <th># SL</th>
                        <th> Name</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($designations  as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>
                                    <a href="{{URL::to('/designation/edit/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="{{URL::to('/designation/delete/'.$row->id)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Add Designations</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{url('/designation/store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder=" Name"/>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">Add Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<div>

</div>
@endsection
