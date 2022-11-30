@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Group')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
	<div class="col-md-6 col-sm-12 m-auto">
		<div class="card">
			<div class="card-header bg-success">
				<h4 class="card-title">Update Account Group</h4>
			</div>
			<div class="card-body">
				<form action="{{url('/update_account_group/'.$oneAccountGroup->id)}}" method="POST">
					@csrf
					<div class="form-group">
						<label for="cono1">Account Group Name*</label>
						<input type="text" name="account_group_name" class="form-control" id="cono1" value="{{$oneAccountGroup->account_group_name}}" />
						@error('account_group_name')
							<strong class="text-danger">{{$message}}</strong>
						@enderror
					</div>
					<div class="form-group">
						<label>Nature*</label>
						<select class="form-control" name="account_group_nature">
							<option  value="{{$oneAccountGroup->account_group_nature}}">{{$oneAccountGroup->account_group_nature}}</option>
							<option value="{{ null }}" >select</option>
							<option value="Assets" >Assets</option>
							<option value="Liabilities">Liabilities</option>
							<option value="Income">Income</option>
							<option value="Expenses">Expenses</option>
						</select>
					</div>
					<div class="form-group">
						<label>Group Under</label>
						<select class="form-control" name="account_group_under_id">
							<option value="" hidden>Select Group</option>
							@foreach($account_group_list as $list_row)
							<option value="{{$list_row->id}}" {{ $oneAccountGroup->account_group_under_id == $list_row->id?'Selected': ' ' }}>{{$list_row->account_group_name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="cono1">Description</label>
						<textarea class="form-control" name="description">{{$oneAccountGroup->description}}</textarea>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-success">Create Unit</button>
						<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
