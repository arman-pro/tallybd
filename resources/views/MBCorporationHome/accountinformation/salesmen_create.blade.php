@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Sale Mane')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
	<div class="col-md-6 col-sm-12 m-auto">
		<div class="card">
			<div class="card-header bg-success">
				<h4 class="card-title">Add Sale Man</h4>
			</div>
			<div class="card-body">
				<form action="{{url('/store_selasman')}}" method="POST">
					@csrf
					<div class="form-group">
						<label for="cono1" class="control-label" >Sale Man Name*</label>
						<input type="text" name="salesman_name" class="form-control" id="cono1" placeholder="Sale Man Name" required />
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label" >Phone Number*</label>
						<input type="text" name="phone" class="form-control" id="cono1" placeholder="Phone Number" required />
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label" >E-mail</label>
						<input type="text" name="email" class="form-control" id="cono1" placeholder="E-mail" />
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label" >Address</label>
						<input type="text" name="address" class="form-control" id="cono1" placeholder="Address" />
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success">Create</button>
						<a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger">Cencel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
<div>

</div>
@endsection
