@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Item Update')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-success">
					<h4 class="card-title">Update Item</h4>
				</div>
				<div class="card-body">
					<form action="{{url('/update_item/'.$item->id)}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1" >Item Name*</label>
									<input type="text" name="name" class="form-control" value="{{$item->name}}" />
									@error('item_name')
										<strong class="text-danger">{{$message}}</strong>
									@enderror
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1" >Unit*</label>
									<select class="form-control" name="unit_id">
										@foreach($units as $unit_row)
											<option value="{{$unit_row->id}}"{{$item->unit_id == $unit_row->id?'Selected':' '  }}>{{$unit_row->name}}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1" >How Many</label>
									<input type="text" name="how_many_unit" class="form-control" value="{{$item->how_many_unit}}"/>
									@error('how_many_unit')
										<strong class="text-danger">{{$message}}</strong>
									@enderror
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group ">
									<label for="cono1">Catagory*</label>
									<select class="form-control" name="category_id">
										@foreach($categories as $cata_row)
										<option value="{{$cata_row->id}}" {{$item->category_id == $cata_row->id?'Selected':' '  }}>{{$cata_row->name}}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1">Purchases Price</label>
									<input type="text" name="purchases_price" class="form-control" value="{{$item->purchases_price}}" />
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1" class="control-label col-form-label" >Sales Price</label>
									<input type="text" name="sales_price" class="form-control" value="{{$item->sales_price}}"/>
								</div>
							</div>
						</div>
						<h4 class="p-1 bg-dark text-light">Previous Stock Details</h4>
						<div class="row">
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1">Godown Name</label>
									<select class="form-control" name="godown_id">
										@foreach($godowns as $godwn_row)
										<option value="{{$godwn_row->id}}"{{ $item->godown_id == $godwn_row->id? 'Selected' : ' '  }}>{{$godwn_row->name}}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1">Previous Stock</label>
									<input type="text" name="previous_stock" class="form-control" value="{{$item->previous_stock}}"/>
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label for="cono1" class="control-label col-form-label" >Total Prevous Stock Value</label>
									<input type="text" name="total_previous_stock_value" class="form-control"value="{{$item->total_previous_stock_value}}"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="cono1" >Description</label>
							<textarea class="form-control" name="item_description">{{$item->item_description}}</textarea>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Update Item</button>
							<a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger">Cencel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>
@endsection
