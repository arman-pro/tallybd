@extends('MBCorporationHome.apps_layout.layout')
@section('title', "All Receive & Payment")

@section('admin_content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <form action="{{url('/all_recevie_payment/by/date')}}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header bg-success text-light">
                        <h4 class="card-title">All Receive & Payment</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-12">
                                <label for="cono1" class="control-label col-form-label">From</label>
                                <input type="Date" class="form-control" name="form_date" />
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label for="cono1" class="control-label col-form-label">To</label>
                                <input type="Date" class="form-control" name="to_date" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success btn-lg text-light fw-bold"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
