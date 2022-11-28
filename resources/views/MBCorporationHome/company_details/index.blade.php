@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Company Details</h4>
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
              			@foreach($CompanyDetails as $row)
              			<form action="{{URL::to('Update_company_details/'.$row->id)}}" method="POST" enctype="multipart/form-data">
							@csrf
              				<h4 class="card-title" style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">Company Details</h4><br>
                    		<br>
                   			<div class="row">



		                   		<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Company Name : *</label>
					                    <div>
					                        <input type="text" name="company_name" class="form-control" id="cono1" value="{{ $row->company_name}}" />
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Country Name : *</label>
					                    <div>
					                        <input type="text" name="contry_name" class="form-control" id="cono1" value="{{ $row->contry_name}}"/>
			                      		</div>
			                      	</div>
			                   	</div>


			                   	<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Mailing Name :</label>
					                    <div>
					                        <input type="text" name="mailing_name" class="form-control" id="cono1" value="{{ $row->mailing_name}}" />
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Email Id : *</label>
					                    <div>
					                        <input type="text" name="email_id" class="form-control" id="cono1" value="{{ $row->email_id}}" />
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Website : *</label>
					                    <div>
					                        <input type="text" name="website_name" class="form-control" id="cono1" value="{{ $row->website_name}}" />
			                      		</div>
			                      	</div>
			                   	</div>


			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Financial Year Setting :</label>

                                          <select class="form-control" name="financial_year_id">
                                              @foreach ($years as $year)
                                                <option value="{{$year->id}}"
                                                   {{$year->id == $row->financial_year_id ? 'Selected': ' '}}
                                                    >{{'From : '. $year->financial_year_from.'- To : '.$year->financial_year_to }}</option>
                                              @endforeach
                                          </select>
			                      	</div>
			                   	</div>


			                   	<div class="col-md-3">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Mobile Number : *</label>
					                    <div>
					                        <input type="text" name="mobile_number" class="form-control" value="{{ $row->mobile_number}}" />
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-12">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Address :</label>
					                    <div>
					                        <textarea class="form-control" name="company_address">
					                        	{{ $row->company_address}}
					                        </textarea>
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-12">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Description :</label>
					                    <div>
					                        <textarea class="form-control" name="company_des">
					                        	{{ $row->company_des}}
					                        </textarea>
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<input type="hidden" name="old_company_logo" value="{{$row->company_logo}}">

			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Update Company Logo :</label>
					                    <div>
					                       <input type="file" name="company_logo" class="form-control">
			                      		</div>
			                      	</div>
			                   	</div>



			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Current Company Logo :</label>
					                    <div>
					                       <img src="{{asset($row->company_logo)}}" style="height: 100px;width: 100px;">
			                      		</div>
			                      	</div>
			                   	</div>
                   			</div>

                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button type="submit" class="btn btn-primary" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Update</button>
                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>

                   			@endforeach
                   		</form>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection


