@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add New Item')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h4 class="card-title">Add New Item</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{url('/store_item')}}" method="POST">
                <input type="hidden" name="date" value="{{App\Companydetail::with('financial_year')->first()->financial_year->financial_year_from }}">
                @csrf                    
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">Item Name*</label>
                            <input type="text" name="name" class="form-control" id="cono1" placeholder="Item Name" autocomplete="off">
                            @error('item_name')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">Unit*</label>
                            <select class="form-control" name="unit_id">
                                <option value="" hidden>Select</option>
                                @foreach($units as $unit_row)
                                <option value="{{$unit_row->id}}">{{$unit_row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">How Many</label>
                            <input type="text" name="how_many_unit" class="form-control" id="cono1" placeholder="How Many" />
                            @error('how_many_unit')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">Catagory*</label>
                            <select class="form-control" name="category_id">
                                <option value="" hidden>Select</option>
                                @foreach($categories as $cata_row)
                                <option value="{{$cata_row->id}}">{{$cata_row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">Purchases Price</label>
                            <input type="text" name="purchases_price" class="form-control" id="cono1" placeholder="Purchases Price" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group ">
                            <label for="cono1" >Sales Price</label>
                            <input type="text" name="sales_price" class="form-control" id="cono1"
                                    placeholder="Sales Price" autocomplete="off">
                        </div>
                    </div>

                </div>
                    <h4 class="p-2 bg-dark text-light"> Previous Stock Details</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cono1" >Godwn Name*</label>
                            <select class="form-control" name="godown_id">
                                <option value="" hidden>Select</option>
                                @foreach($godowns as $godown_row)
                                <option value="{{$godown_row->id}}">{{$godown_row->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cono1" >Previous Stock*</label>
                            <input type="text" name="previous_stock" class="form-control" id="cono1"
                                    placeholder="Prevous Stock" /autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cono1" >Total Prevous Stock Value</label>
                                <input type="text" name="total_previous_stock_value" class="form-control"
                                id="cono1" placeholder="Total Prevous Stock Value" /autocomplete="off">
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="cono1" >Description</label>
                            <textarea class="form-control" name="item_description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success" >Create Item</button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger">Cencel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
