@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Company SaleMan</h4>
	</div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
              		<div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">
              			@foreach($one_SaleMan as $row)
              			<form action="{{url('/update_SaleMan/'.$row->salesman_id)}}" method="POST">
							@csrf
              				<h4 class="card-title" style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">Update SaleMan </h4><br>
                    		<br>
                   			<div class="row">

		                   		<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >SaleMan Name :*</label>
					                    <div>
					                        <input type="text" name="salesman_name" class="form-control" value="{{$row->salesman_name}}" />
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Phone Number :*</label>
					                    <div>
					                        <input type="text" name="phone" class="form-control" value="{{$row->phone}}" />
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >E-mail :</label>
					                    <div>
					                        <input type="text" name="email" class="form-control" value="{{$row->email}}" />
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" > Address :</label>
					                    <div>
					                        <input type="text" name="address" class="form-control" value="{{$row->address}}" />
			                      		</div>
			                      	</div>
			                   	</div>

			                   	@endforeach
                   			</div>
                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button type="submit" class="btn btn-primary" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Update</button>
                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>
                   		</form>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection
