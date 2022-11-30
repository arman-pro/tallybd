@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Godown')
@section('admin_content')

<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
	<div class="col-md-6 col-sm-12 m-auto">
		<div class="card">
			<div class="card-header bg-success">
				<h4 class="card-title">Add Godown</h4>
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
				<form action="{{ url('store_godown_create_from')}}" method="POST">
					@csrf
					<div class="form-group">
						<label for="cono1" class="control-label col-form-label" >Godown Name</label>
						<input type="text" name="name" class="form-control" id="cono1" placeholder="Godown Name" />
					</div>
					<div class="form-group">
						<label for="cono1" class="control-label col-form-label" >Description</label>
						<textarea class="form-control" name="description" placeholder="Description"></textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success">Create</button>
						<a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger">Cencel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
