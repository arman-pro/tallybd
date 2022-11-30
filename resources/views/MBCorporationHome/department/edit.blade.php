@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Edit Department')
@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">All Department Manage</h4>
            </div>
            <div class="card-body">
                <table class="table table-resposive table-bordered">
                    <thead class="bg-light text-dark">
                        <th># SL</th>
                        <th> Name</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($departments as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>
                                    <a href="{{URL::to('/department/edit/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="{{URL::to('/department/delete/'.$row->id)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Edit Department</h4>
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
                @foreach($oneDepartment as $one_row)
                    <form action="{{url('/department/update/'.$one_row->id)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" value="{{$one_row->name}}" />
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
<div>
</div>
@endsection
