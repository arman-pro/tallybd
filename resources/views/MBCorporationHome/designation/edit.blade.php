@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Designation Edit')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card overflow-auto">
            <div class="card-header bg-success">
                <h4 class="card-title">All Designatins Manage</h4>
            </div>
            <div class="card-body">
                <table class="table table-resposive table-bordered">
                    <thead class="bg-light text-dark">
                        <th># SL</th>
                        <th>Designation Name</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($designations as $row)
                        <tr style="text-align: center;">
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

    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Designation Edit</h4>
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
                @foreach($oneDesignation as $one_row)
                    <form action="{{url('/designation/update/'.$one_row->id)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" value="{{$one_row->name}}" />
                        </div>                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update Designation</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
<div>
</div>
@endsection
