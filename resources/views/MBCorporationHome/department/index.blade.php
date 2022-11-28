@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; ">Department Information</h4>
    </div>
</div>


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All Department Manage</h4>

                    <table class="table table-resposive table-bordered" id="example">
                        <thead style="background-color: #566573;text-align: center;">
                            <th style="color: #fff;"># SL</th>
                            <th style="color: #fff;"> Name</th>

                            <th style="color: #fff;">Action</th>
                        </thead>
                        <tbody>

                        @foreach($departments as $row)
                            <tr style="text-align: center;">
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

            <div class="col-md-4">
              <div class="card">
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
                            <form action="{{url('/department/store')}}" method="POST">
                        @csrf
                                <h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633; padding: 5px; color: #fff;">Add Department</h4><br>

                                <input class="form-control" type="text" name="name" placeholder=" Name">
                                <br>
                                <br>
                                <button class="btn btn-success" style="color: #fff;">Add Department</button>
                            </form>

                        </div>
                    </div>
              </div>
            </div>
        <div>

</div>
@endsection
