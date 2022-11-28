@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
              		<div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">

              			<form action="{{url('/store_selasman')}}" method="POST">
							@csrf
              				<h4 class="card-title" style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">Add SaleMan </h4><br>
                    		
                   			<div class="row">

		                   		<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >SaleMan Name :*</label>
					                    <div>
					                        <input type="text" name="salesman_name" class="form-control" id="cono1" placeholder="SaleMan Name" />
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Phone Number :*</label>
					                    <div>
					                        <input type="text" name="phone" class="form-control" id="cono1" placeholder="Phone Number" />
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >E-mail :</label>
					                    <div>
					                        <input type="text" name="email" class="form-control" id="cono1" placeholder="E-mail" />
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" > Address :</label>
					                    <div>
					                        <input type="text" name="address" class="form-control" id="cono1" placeholder="Address" />
			                      		</div>
			                      	</div>
			                   	</div>


                   			</div>
                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button type="submit" class="btn btn-success" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Create</button>
                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>
                   		</form>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection
