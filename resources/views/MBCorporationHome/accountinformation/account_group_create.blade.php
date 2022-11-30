@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Account Group')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
	<div class="col-md-6 col-sm-12 m-auto">
		<div class="card">
			<div class="card-header bg-success">
				<h4 class="card-title">Add Account Group</h4>
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
				<form action="{{url('/store_account_group')}}" method="POST">
					@csrf
					<div class="form-group">
						<label for="cono1">Account Group Name*</label>
						<input type="text" name="account_group_name" class="form-control" id="cono1" placeholder="Account Group Name" required />
						@error('account_group_name')
							<strong class="text-danger">{{$message}}</strong>
						@enderror
					</div>
					<div class="form-group mg-b-10-force">
						<label>Nature*</label>
						<select class="form-control" name="account_group_nature" required>
							<option value="{{ null}}">select</option>
							<option value="Assets" >Assets</option>
							<option value="Liabilities">Liabilities</option>
							<option value="Income">Income</option>
							<option value="Expenses">Expenses</option>
						</select>
					</div>
					<div class="form-group">
						<label>Group Under</label>
						<select class="form-control" name="account_group_under_id">
							<option value="{{ null}}" >select</option>
							@foreach($account_group_list as $lis_row)
							<option value="{{$lis_row->id}}">{{$lis_row->account_group_name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label>Description :</label>
						<textarea class="form-control" name="description"></textarea>
					</div>
					
					<div class="form-group">
						<button type="submit" class="btn btn-success"><b>Create</b></button>
						<a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cencel</b></a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
