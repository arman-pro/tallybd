@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Company Item List</h4>
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

              			<form action="{{url('/update_item/'.$item->id)}}" method="POST">
							@csrf
              				<h3 class="card-title" style=" font-weight: 600; background-color: #69C6E0; padding-top: 20px;color: #fff;border-radius: 5px;text-align: center;height: 70px;">Update Item</h3><br>
                    		<br>
                   			<div class="row">

		                   		<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Item Name : *</label>
					                    <div>
					                        <input type="text" name="name" class="form-control" value="{{$item->name}}" />
					                        @error('item_name')
						                  		<strong class="text-danger">{{$message}}</strong>
						                  	@enderror
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-1">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Unit: *</label>
					                    <div>
					                        <select class="form-control" name="unit_id">
					                        	@foreach($units as $unit_row)
					                        	    <option value="{{$unit_row->id}}"{{$item->unit_id == $unit_row->id?'Selected':' '  }}>{{$unit_row->name}}</option>
					                        	@endforeach
					                        </select>

			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-3">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >How Many: </label>
					                    <div>
					                        <input type="text" name="how_many_unit" class="form-control" value="{{$item->how_many_unit}}"/>
					                        @error('how_many_unit')
						                  		<strong class="text-danger">{{$message}}</strong>
						                  	@enderror
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Catagory : *</label>
					                    <div>
					                        <select class="form-control" name="category_id">

					                        	@foreach($categories as $cata_row)
					                        	<option value="{{$cata_row->id}}" {{$item->category_id == $cata_row->id?'Selected':' '  }}>{{$cata_row->name}}</option>
					                        	@endforeach
					                        </select>


			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Purchases Price :</label>
					                    <div>
					                        <input type="text" name="purchases_price" class="form-control" value="{{$item->purchases_price}}" />
			                      		</div>
			                      	</div>
			                   	</div>
			                   	<div class="col-md-6">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Sales Price :</label>
					                    <div>
					                        <input type="text" name="sales_price" class="form-control" value="{{$item->sales_price}}"/>
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<h4 style=" font-weight: 600; background-color: #7765;color: #000;border-radius: 5px;text-align: center;">Previous Stock Details</h4><br>
			                   	<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Godown Name:</label>
					                    <div>
					                        <select class="form-control" name="godown_id">
					                        	@foreach($godowns as $godwn_row)
					                        	<option value="{{$godwn_row->id}}"{{ $item->godown_id == $godwn_row->id? 'Selected' : ' '  }}>{{$godwn_row->name}}</option>
					                        	@endforeach

					                        </select>
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Previous Stock:</label>
					                    <div>
					                         <input type="text" name="previous_stock" class="form-control" value="{{$item->previous_stock}}"/>
			                      		</div>
			                      	</div>
			                   	</div>

			                   	<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Total Prevous Stock Value:</label>
					                    <div>
					                       <input type="text" name="total_previous_stock_value" class="form-control"value="{{$item->total_previous_stock_value}}"/>
			                      		</div>
			                      	</div>
			                   	</div>


			                   		<div class="col-md-4">
		                   			<div class="form-group row">
					                    <label for="cono1" class="control-label col-form-label" >Description :</label>
					                    <div>
					                        <textarea class="form-control" name="item_description">
					                        	{{$item->item_description}}
					                        </textarea>
			                      		</div>
			                      	</div>
			                   	</div>
                   			</div>


                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button type="submit" class="btn btn-primary" style="width: 250px;color:#fff; font-weight: 800;font-size: 16px;">Update Item</button>
                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>
                   		</form>
              		</div>
              </div>
            </div>
        <div>

</div>
@endsection
