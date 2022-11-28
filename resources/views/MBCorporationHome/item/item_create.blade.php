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

                    <form action="{{url('/store_item')}}" method="POST">
                        <input type="hidden" name="date" value="{{App\Companydetail::with('financial_year')->first()->financial_year->financial_year_from }}">
                        @csrf
                        <h3 class="card-title"
                            style=" font-weight: 600; background-color: #69C6E0; padding-top: 20px;color: #fff;border-radius: 5px;text-align: center;height: 70px;">
                            Add New Item</h3><br>
                            
                        <div class="row">

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Item Name : *</label>
                                    <div>
                                        <input type="text" name="name" class="form-control" id="cono1"
                                            placeholder="Item Name" /autocomplete="off">
                                        @error('item_name')
                                        <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Unit: *</label>
                                    <div>
                                        <select class="form-control" name="unit_id">
                                            <option>Select</option>
                                            @foreach($units as $unit_row)
                                            <option value="{{$unit_row->id}}">{{$unit_row->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">How Many: </label>
                                    <div>
                                        <input type="text" name="how_many_unit" class="form-control" id="cono1"
                                            placeholder="How Many" />
                                        @error('how_many_unit')
                                        <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Catagory : *</label>
                                    <div>
                                        <select class="form-control" name="category_id">
                                            <option>Select</option>
                                            @foreach($categories as $cata_row)
                                            <option value="{{$cata_row->id}}">{{$cata_row->name}}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Purchases Price :</label>
                                    <div>
                                        <input type="text" name="purchases_price" class="form-control" id="cono1"
                                            placeholder="Purchases Price" /autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Sales Price :</label>
                                    <div>
                                        <input type="text" name="sales_price" class="form-control" id="cono1"
                                            placeholder="Sales Price" /autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <h4
                                style=" font-weight: 600; background-color: #7765;color: #000;border-radius: 5px;text-align: center;">
                                Previous Stock Details</h4><br>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Godwn Name: *</label>
                                    <div>
                                        <select class="form-control" name="godown_id">
                                            <option value=" ">Select</option>
                                            @foreach($godowns as $godown_row)
                                            <option value="{{$godown_row->id}}">{{$godown_row->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Previous Stock: *</label>
                                    <div>
                                        <input type="text" name="previous_stock" class="form-control" id="cono1"
                                            placeholder="Prevous Stock" /autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Total Prevous Stock
                                        Value:</label>
                                    <div>
                                        <input type="text" name="total_previous_stock_value" class="form-control"
                                            id="cono1" placeholder="Total Prevous Stock Value" /autocomplete="off">
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Description :</label>
                                    <div>
                                        <textarea class="form-control" name="item_description">

					                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div style="text-align: center; color: #fff; font-weight: 800;">
                            <button type="submit" class="btn btn-success"
                                style="width: 250px;color:#fff; font-weight: 800;font-size: 16px;">Create Item</button>
                            <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>

        </div>
        @endsection
