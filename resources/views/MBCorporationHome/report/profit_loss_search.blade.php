@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div style="background: #fff;">
	<h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Profit & Loss</h3>
	<div class="row">
		
		<form action="{{url('/profit_loss/by/date')}}" method="POST">
			@csrf
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-3">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >From :</label>
			        	<div>
			           		<input type="Date" class="form-control" name="form_date">
					    </div>
					</div>
				</div>

				<div class="col-md-3">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >To :</label>
			        	<div>
			           	<input type="Date" class="form-control" name="to_date">
					    </div>
					</div>
				</div>
				<div class="col-md-12" style="text-align: center;">
					<br>
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>
			</div>
		</form>
		
	<br>
	
                   				
	</div>
</div>

@endsection
