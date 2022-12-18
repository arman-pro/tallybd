@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Salary Report")
@section('admin_content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-10 m-auto">
			<form action="{{url('salary/employee/salary-by/date')}}" method="GET">
			<div class="card">
				<div class="card-header bg-success text-light">
					<h4 class="card-title"> Employee Account Ledger</h4>
					<p class="m-0">Ledger Particular Searching</p>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<div class="col-md-4 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >Employee Ledger</label>
							<select 
								class="form-control" name="ledger_id" id="ledger_id" matcher
								data-placeholder="Select Employee Ledger"
							>
								<option value="" hidden>Select Employee Ledger</option>
								@php
									$account_name =App\Employee::get(['id', 'name']);
								@endphp
								@foreach($account_name as $account_name_row)
									<option value="{{$account_name_row->id}}">{{$account_name_row->name}}</option>
								@endforeach
							</select>
						</div>
		
						<div class="col-md-4 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >From</label>
							<input type="Date" class="form-control" name="from_date" required />
						</div>
		
						<div class="col-md-4 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >To</label>
							<input type="Date" class="form-control" name="to_date" required />
						</div>
					</div>					
				</div>
				<div class="card-footer text-center">
					<button type="submit" class="btn btn-success text-light">Search</button>
				</div>
			</div>
			</form>
		</div>        
	</div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function(){
        $('#ledger_id').select2();
    });
</script>
@endpush
