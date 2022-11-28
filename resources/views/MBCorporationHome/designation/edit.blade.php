@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; ">Designations</h4>
	</div>
</div>


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-9">
              <div class="card">
              	<div class="card-body">
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All Designations Manage</h4>

              		<table class="table table-resposive table-bordered">
                    	<thead style="background-color: #566573;text-align: center;">
                    		<th style="color: #fff;"># SL</th>
                    		<th style="color: #fff;">Designation Name</th>

                    		<th style="color: #fff;">Action</th>
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

            <div class="col-md-3">
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
                        @foreach($oneDesignation as $one_row)
              				<form action="{{url('/designation/update/'.$one_row->id)}}" method="POST">
                        @csrf
              					<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633; padding: 5px; color: #fff;">Update Catagory</h4><br>

	              				<input class="form-control" type="text" name="name" value="{{$one_row->name}}">
	              				<br>
	              				<br>
	              				<button class="btn btn-primary" style="color: #fff;">Update designation</button>
              				</form>
              			@endforeach
              			</div>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection
