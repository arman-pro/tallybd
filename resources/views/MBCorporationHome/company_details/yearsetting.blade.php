@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Financial Year Setting</h4>
	</div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                  <div class="card-header text-center" style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                    Financial Year Setting
                  </div>
              		<div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">

              			<form action="{{URL::to('year-setting/store')}}" method="POST" enctype="multipart/form-data">
							@csrf
                   			<div class="row">
			                   	<div class="col-md-3">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Financial Year From :</label>
					                    <div>
					                        <input  required="true"  type="date"  class="form-control" name="financial_year_from" value="{{ old('financial_year_from') }}">
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-3">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Financial Year To :</label>
					                    <div>
					                         <input  required="true"  type="date"  class="form-control  " name="financial_year_to" value="{{ old('financial_year_to') }}">
			                      		</div>
			                      	</div>
			                   	</div>

                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="cono1" class="control-label col-form-label" >&nbsp;</label>
                                        <button type="submit" class="btn btn-primary" style="color:#fff;">Add</button>
                                    </div>
                                </div>
                   			</div>

                   		</form>
              		</div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                  <div class="card-header text-center" style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                    Financial Years
                  </div>
              		<div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">

                        <table class="table table-striped table-inverse table-responsive">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Sl.</th>
                                    <th>Financial Year From :</th>
                                    <th>Financial Year To :</th>
                                    <th>Active/Deactive</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($financialYear as $key=>$year)
                                        <tr>
                                            <td scope="row">{{ $key+1 }}</td>
                                            <td>{{ $year->financial_year_from??' ' }}</td>
                                            <td>{{ $year->financial_year_to??' ' }}</td>
                                            <td>
                                                <a href="{{ url('active-change-year', $year->id) }}">
                                                    @if ($year->status)
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-close" aria-hidden="true"></i>
                                                    @endif

                                                </a>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                        </table>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection


