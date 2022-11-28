@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Company Account Group</h4>
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

              			<form action="{{url('/update_account_group/'.$oneAccountGroup->id)}}" method="POST">
							@csrf
              				<h4 class="card-title" style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">Upadate Account Group </h4><br>
                    		<br>
                   			<div class="row">

		                   		<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Account Group Name :*</label>
					                    <div>
					                        <input type="text" name="account_group_name" class="form-control" id="cono1" value="{{$oneAccountGroup->account_group_name}}" />
					                         @error('account_group_name')
						                  		<strong class="text-danger">{{$message}}</strong>
						                  	@enderror
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-lg-6">
						                <div class="form-group mg-b-10-force">
						                  <label class="form-control-label">Nature : *<span class="tx-danger">*</span></label>
						                  <select class="form-control" name="account_group_nature">
						                    <option  value="{{$oneAccountGroup->account_group_nature}}">{{$oneAccountGroup->account_group_nature}}</option>
						                    <option value="{{ null }}" >select</option>
						                    <option value="Assets" >Assets</option>
						                    <option value="Liabilities">Liabilities</option>
						                    <option value="Income">Income</option>
						                    <option value="Expenses">Expenses</option>
						                  </select>

						                </div>
						              </div><!-- col-4 -->

						              <div class="col-lg-6">
						                <div class="form-group mg-b-10-force">
						                  <label class="form-control-label">Group Under : <span class="tx-danger"></span></label>
						                  <select class="form-control" name="account_group_under_id">
						                    {{-- <option value="{{$oneAccountGroup->account_group_under}}">{{$oneAccountGroup->account_group_under}}</option> --}}
						                    <option value="{{ null }}">Select</option>
						                    @foreach($account_group_list as $list_row)
						                    <option value="{{$list_row->id}}"
                                                {{ $oneAccountGroup->account_group_under_id == $list_row->id?'Selected': ' ' }}>{{$list_row->account_group_name}}</option>
						                    @endforeach
						                  </select>

						                </div>
						              </div><!-- col-4 -->



			                   		<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Description :</label>
					                    <div>
					                        <textarea class="form-control" name="description">
					                        	{{$oneAccountGroup->description}}
					                        </textarea>
			                      		</div>
			                      	</div>
			                   	</div>
                   			</div>

                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button type="submit" class="btn btn-success" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Create Unit</button>
                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>
                   		</form>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection
