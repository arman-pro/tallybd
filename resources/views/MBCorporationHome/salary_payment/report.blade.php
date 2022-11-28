@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;margin-bottom: 1250px;">
	<h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Account Ledger</h3>
	<div class="row">

        <strong class="text-center  text-info">Ledger Particular Searching</strong>

		<form action="{{url('salary/employee/salary-by/date')}}" method="GET" style="margin-top: 20px;">

			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-3">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >Employee Ledger :</label>
			        	<div>
			           		<select class="form-control" name="ledger_id" required>
			           			<option>Select</option>
                                @php
                                    $account_name =App\Employee::get();
                                @endphp
                                @foreach($account_name as $account_name_row)
                                    <option value="{{$account_name_row->id}}">{{$account_name_row->name}}</option>
                                @endforeach
			           		</select>
					    </div>
					</div>
				</div>

				<div class="col-md-2">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >From :</label>
			        	<div>
			           		<input type="Date" class="form-control" name="from_date" required>
					    </div>
					</div>
				</div>

				<div class="col-md-2">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >To :</label>
			        	<div>
			           	<input type="Date" class="form-control" name="to_date" required>
					    </div>
					</div>
				</div>
				<div class="col-md-12" style="text-align: center;">
					<br>
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>
			</div>
		</form>

        <strong class="text-center  text-info" style="margin-top: 20px;">Ledger Group Searching</strong>
        <form action="{{ route('account_ledger_group_search_from')}}" method="POST" >
			@csrf
			<div class="row">
				<div class="col-md-2"></div>

				<div class="col-md-3">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >Account Group Ledger :</label>
			        	<div>
			           		<select class="form-control" name="account_name" required>
			           			<option>Select</option>

			           		{{-- @foreach($account_group_list as $account_name_row)
			           			<option value="{{$account_name_row->id}}">{{$account_name_row->account_group_name}}</option>
			           		@endforeach --}}
			           		</select>
					    </div>
					</div>
				</div>

				<div class="col-md-2">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >From :</label>
			        	<div>
			           		<input type="Date" class="form-control" name="form_date" required>
					    </div>
					</div>
				</div>

				<div class="col-md-2">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >To :</label>
			        	<div>
			           	<input type="Date" class="form-control" name="to_date" required>
					    </div>
					</div>
				</div>
				<div class="col-md-12" style="text-align: center;">
					<br>
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>
			</div>
		</form>

	</div>
</div>

@endsection
