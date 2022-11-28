@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;margin-bottom: 1250px;">
	<h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Account Ledger</h3>
	<div class="row">

        <strong class="text-center  text-info">Ledger Particular Searching</strong>
        @if ($type == null)
		<form action="{{url('/account_ledger_report/by/date')}}" method="POST" style="margin-top: 20px;">
        @else
		<form action="{{url('bankinterest/get')}}" method="POST" style="margin-top: 20px;">

        @endif
			@csrf
			<div class="row">
				<div class="col-md-1"></div>

				<div class="col-md-3">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >Account Ledger :</label>
			        	<div>
                            <select  name="ledger_id" id="ledger_id"  style="width: 100%" required>
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
			           	<input type="Date" class="form-control" name="to_date" value="{{ date('Y-m-d') }}" required>
					    </div>
					</div>
				</div>
                @if($type != null)
                    <div class="col-md-2">
                        <div class="form-group row">
                            <label for="cono1" class="control-label col-form-label" >Percent :</label>
                            <div>
                            <input type="number" class="form-control" name="percent" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row">
                            <label for="cono1" class="control-label col-form-label" >Bank Days :</label>
                            <div>
                            <input type="number" class="form-control" name="bankDays" required>
                            </div>
                        </div>
                    </div>
                @endif
				<div class="col-md-12" style="text-align: center;">
					<br>
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>
			</div>
		</form>

        @if($type == null)
        <strong class="text-center  text-info" style="margin-top: 20px;">Ledger Group Searching</strong>
        <form action="{{ route('account_ledger_group_search_from')}}" method="POST" >
			@csrf
			<div class="row">
				<div class="col-md-2"></div>

				<div class="col-md-3">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >Account Group Ledger :</label>
			        	<div>
                            <select  name="account_name" id="account_name"  style="width: 100%" required>
                            </select>
					    </div>
					</div>
				</div>

				<div class="col-md-2">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >From :</label>
			        	<div>
			           		<input type="Date" class="form-control" name="form_date"  value="{{ date('Y-m-d') }}"required>
					    </div>
					</div>
				</div>

				<div class="col-md-2">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >To :</label>
			        	<div>
			           	<input type="Date" class="form-control" name="to_date"  value="{{ date('Y-m-d') }}"required>
					    </div>
					</div>
				</div>
				
				<div class="col-md-2">
				    <div class="form-group">
			       		<label class="control-label col-form-label" >Filter</label>
			        	<select class="form-control" name="filter">
			        	    <option value="" hidden>Select Filter</option>
			        	    <option value="all">All</option>
			        	    <option value="filter">Filter</option>
			        	</select>
					</div>
				</div>

				<div class="col-md-12" style="text-align: center;">
					<br>
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>
			</div>
		</form>
        @endif

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
