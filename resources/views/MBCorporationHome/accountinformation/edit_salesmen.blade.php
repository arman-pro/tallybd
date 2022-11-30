@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Company Sale Man')
@section('admin_content')


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
	<div class="col-md-6 col-sm-12 m-auto">
		<div class="card">
			<div class="card-header bg-success">
				<h4 class="card-title">Update Sale Man</h4>
			</div>
			<div class="card-body">
				@foreach($one_SaleMan as $row)
				<form action="{{url('/update_SaleMan/'.$row->salesman_id)}}" method="POST">
					@csrf
					<div class="form-group">
						<label for="cono1" class="control-label" >Sale Man Name*</label>
						<input type="text" name="salesman_name" class="form-control" value="{{$row->salesman_name}}" required/>
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label" >Phone Number*</label>
						<input type="text" name="phone" class="form-control" value="{{$row->phone}}" required/>
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label" >E-mail</label>
						<input type="text" name="email" class="form-control" value="{{$row->email}}" />
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label col-form-label" >Address</label>
						<input type="text" name="address" class="form-control" value="{{$row->address}}" />
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">Update</button>
						<a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger">Cencel</a>
					</div>
				</form>
				@endforeach
			</div>
		</div>
	</div>
<div>
</div>
@endsection
