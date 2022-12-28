@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Sale Report Search")

@push('css')
<style type="text/css">
    table, td, th {
      border: 1px solid #000;
    }
    
    table { 
      border-collapse: collapse;
    }
</style>
@endpush

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        {{-- <div class="col-sm-2">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary text-light fw-bold btn-lg" href="{{route('all_sales_report')}}">All Sales</a>
                    <a class="btn btn-success text-light fw-bold btn-lg" href="{{route('item_wise_sales_report_search_form')}}">Item Wise Sales</a>
                    <a class="btn btn-danger text-light fw-bold btn-lg" href="{{route('party_wise_sales_report_search')}}">Party Wise Sales </a>
                    <a class="btn btn-info text-light fw-bold btn-lg" href="{{route('sale_man_wise_sales_report_search')}}">Sele Man Wise Sales </a>
                </div>
            </div>
        </div> --}}
        {{-- all sale report form --}}
        <div class="col-sm-12 ">
            <form action="{{url('/all_sales_report/by/date')}}" method="GET">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">All Sale Report</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">From</label>
                            <input type="Date" class="form-control" name="form_date">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="Date" class="form-control" name="to_date">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-info btn-lg text-light fw-bold"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>

        {{-- item wise sale --}}
        <div class="col-sm-12">
            <form action="{{url('/item_wise_sales_report/by/item')}}" method="GET">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Item Wise Sale Report</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">Item Name</label>
                            <select 
                                class="form-control select2" name="item_name" id="item_name" required
                                data-placeholder="Select a Item Name"
                            >
                                <option value="" hidden>Select a Item Name</option>
                                @php
                                $Item = App\Item::get();
                                @endphp
                                @foreach($Item as $Item_row)
                                <option value="{{$Item_row->id}}">{{$Item_row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">From</label>
                            <input type="Date" class="form-control" name="form_date" required />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="Date" class="form-control" name="to_date" required />
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success btn-lg text-light fw-bold" ><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>

        {{-- Party wise Sale Report --}}
        <div class="col-sm-12">
            <form action="{{url('/party_wise_sales_report')}}" method="GET">
            <div class="card">
                <div class="card-header bg-danger text-light">
                    <h4 class="card-title">Party Wise Sale Report</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">Party Name</label>
                            <select 
                                class="form-control select2" name="account_ledger_id" id="account_ledger_id" required
                                data-placeholder="Select a Party Name"
                            >
                                <option value="" hidden>Select a Party Name</option>
                                @php
                                $AccountLedger = App\AccountLedger::get();
                                @endphp
                                @foreach($AccountLedger as $AccountLedger_row)
                                <option value="{{$AccountLedger_row->id}}">
                                    {{$AccountLedger_row->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">From</label>
                            <input type="Date" class="form-control" name="from_date" required />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="date" class="form-control" name="to_date" required />
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-danger btn-lg text-light fw-bold"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>

        {{-- Sale Man Wise Sale --}}
        <div class="col-sm-12">
            <form action="{{url('/sale_man_wise_sales_report')}}" method="GET">
                
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title">Sale Man Wise Sale Report</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">Sale Man Name</label>
                            <select 
                                class="form-control select2" name="sale_man_id" required
                                data-placeholder="Select a Sale Man Name"
                            >
                                <option value="" hidden>Select a Sale Man Name</option>
                                @php
                                $saleMens = App\SaleMen::get(['id', 'salesman_name']);
                                @endphp
                                @foreach($saleMens as $saleMen)
                                <option value="{{$saleMen->id}}">
                                    {{$saleMen->salesman_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">From</label>
                            <input type="Date" class="form-control" name="from_date" required />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="date" class="form-control" name="to_date" required />
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary btn-lg text-light fw-bold"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    {{--  --}}

</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){
      $(".select2").select2();
    })
    function printData()
    {
        var divToPrint = document.getElementById('printArea');
        var htmlToPrint = '' +
            '<style type="text/css">' +
            'table th, table td {' +
            'border:1px solid #000;' +
            '}' +
            'table{'+
            'border-collapse: collapse;'+
            '}'+
            '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin = window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();

    }
    </script>
@endpush

