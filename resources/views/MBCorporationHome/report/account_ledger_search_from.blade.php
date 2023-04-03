@extends('MBCorporationHome.apps_layout.layout')
@section("tilte", "Account Ledger Report")

@section('admin_content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			@if ($type == null)
			<form action="{{route('account_ledger_report')}}" method="GET" >
			@else
			<form action="{{url('bankinterest/get')}}" method="GET">
			@endif
				
			<div class="card">
				<div class="card-header bg-success text-light" >
				    @if($type == null)
					    <h4 class="card-title">Account Ledger</h4>
					@else 
					    <h4 class="card-title">Bank Interest Calculation</h4>
					@endif
					<p class="m-0">Ledger Particular Searching</p>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<div class="col-md-4 col-sm-12" style="font-size:15px;font-weight:bold;">
							<label for="cono1" class="control-label col-form-label" >Account Ledger</label>
							<select  
								name="ledger_id" id="ledger_id"  style="width: 100%" required
								data-placeholder="Select a Account Ledger"
							>
							</select>
						</div>
		
						<div class="col-md-4 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >From</label>
							<input type="Date" class="form-control" name="form_date" required/>
						</div>
		
						<div class="col-md-4 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >To</label>
							<input type="Date" class="form-control" name="to_date" value="{{ date('Y-m-d') }}" required/>
						</div>
						@if($type != null)
							<div class="col-md-4 col-sm-12">
								<label for="cono1" class="control-label col-form-label" >Percent</label>
								<input type="number" class="form-control" name="percent" required/>
							</div>
							<div class="col-md-4 col-sm-12">
								<label for="cono1" class="control-label col-form-label" >Bank Days</label>
								<input type="number" class="form-control" name="bankDays" required/>
							</div>
						@endif
						
					</div>
				</div>
				<div class="card-footer text-center">
					<button type="submit" class="btn btn-success btn-lg fw-bold text-light"><i class="fa fa-search"></i> Search</button>
				</div>
			</div>
			</form>
		</div>
		<div class="col-sm-12 col-md-12"style="font-size:15px;font-weight:bold;">
			@if($type == null)
			<form action="{{ route('account_ledger_group_search_from')}}" method="GET" >					
			<div class="card">
				<div class="card-header bg-success text-light">
					<h4 class="card-title">Account Group Ledger</h4>
					<p class="m-0">Group Particular Searching</p>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<div class="col-md-3 col-sm-12">
							<div class="form-group">
								<label for="cono1" class="control-label col-form-label" >Account Group Ledger</label>
								<select  
									name="account_name" id="account_name"  style="width: 100%" required
									class="form-control" data-placeholder="Select  Group Ledger"
								>
								</select>
							</div>
						</div>
		
						<div class="col-md-3 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >From</label>
							<input type="Date" class="form-control" name="form_date"  value="{{ date('Y-m-d') }}"required>
						</div>
		
						<div class="col-md-3 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >To</label>
							<input type="Date" class="form-control" name="to_date"  value="{{ date('Y-m-d') }}"required/>
						</div>
						
						<div class="col-md-3 col-sm-12">
							<label class="control-label col-form-label" >Filter</label>
							<select class="form-control" name="filter">
								<option value="" hidden>Select Filter</option>
								<option value="all">All</option>
								<option value="filter">Filter</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card-footer text-center">
					<button type="submit" class="btn btn-success btn-lg fw-bold text-light"><i class="fa fa-search"></i> Search</button>
				</div>
			</div>
			</form>
			@endif
		</div>
		
		<div class="col-sm-12 col-md-12"style="font-size:15px;font-weight:bold;">
			<form action="{{ route('account-group-ledger-detail-report')}}" method="GET" >					
			<div class="card">
				<div class="card-header bg-primary text-light">
					<h4 class="card-title">Account Group Ledger (Details Report)</h4>
					<p class="m-0">Group Particular Searching</p>
				</div>
				<div class="card-body">
					<div class="form-group row">
						<div class="col-md-3 col-sm-12">
							<div class="form-group">
								<label for="cono1" class="control-label col-form-label" >Account Group Ledger</label>
								<select  
									name="account_name" id="account_name_2"  style="width: 100%" required
									class="form-control" data-placeholder="Select  Group Ledger"
								>
								</select>
							</div>
						</div>
		
						<div class="col-md-3 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >From</label>
							<input type="Date" class="form-control" name="form_date"  value="{{ date('Y-m-d') }}"required>
						</div>
		
						<div class="col-md-3 col-sm-12">
							<label for="cono1" class="control-label col-form-label" >To</label>
							<input type="Date" class="form-control" name="to_date"  value="{{ date('Y-m-d') }}"required/>
						</div>
						
						<div class="col-md-3 col-sm-12">
							<label class="control-label col-form-label" >Filter</label>
							<select class="form-control" name="filter">
								<option value="" hidden>Select Filter</option>
								<option value="all">All</option>
								<option value="filter">Filter</option>
							</select>
						</div>
					</div>
				</div>
				<div class="card-footer text-center">
					<button type="submit" class="btn btn-primary btn-lg fw-bold text-light"><i class="fa fa-search"></i> Search</button>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>


@endsection

@push('js')
    <script>
        $("#account_name").select2(
        {
            ajax: {
                url: '{{ url("activeGroup") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {

                	var res = data.groups.map(function (item) {
                        	return {id: item.id, text: item.account_group_name};
                        });
                    return {
                        results: res
                    };
                }
            },

        });
        
        $("#account_name_2").select2(
        {
            ajax: {
                url: '{{ url("activeGroup") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {

                	var res = data.groups.map(function (item) {
                        	return {id: item.id, text: item.account_group_name};
                        });
                    return {
                        results: res
                    };
                }
            },

        });

        $( "#foo" ).on( "click", function() {
        alert( $( this ).text() );
        });
        $("#ledger_id").select2(
        {
            ajax: {
                url: '{{ url("activeLedger") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {

                	var res = data.ledgers.map(function (item) {
                        	return {id: item.id, text: item.account_name};
                        });
                    return {
                        results: res
                    };
                }
            },

        });
    </script>
@endpush
