@extends('MBCorporationHome.apps_layout.layout')
@section("title", "All Purchase Report")

@section('admin_content')
<style type="text/css">
    table, tr, td, th {
      border: 1px solid #000;
    }
    
    table { 
      border-collapse: collapse;
    }
    .topnav {
        overflow: hidden;
        background-color: #eee;
    }

    .topnav a {
        width: 33.33%;
        float: left;
        color: #000;
        text-align: center;
        padding: 5px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .topnav a:hover {
        background-color: #ddd;
        color: black;
    }

    .topnav a.active {
        background-color: #99A3A4;
        color: #fff;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary fw-bold" href="{{route('all_purchases_report')}}">All Purchase</a>
                    <a class="btn btn-success fw-bold text-light" href="{{route('item_wise_purchases_report_search_form')}}">Item Wise Purchase</a>
                    <a class="btn btn-danger fw-bold" href="{{route('party_wise_purchases_report_search')}}">Party Wise Purchase </a>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <form action="{{url('/all_purchases_report/by/date')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">All Purchase Report</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">From</label>
                            <input type="Date" class="form-control" name="form_date" />
                        </div>        
                        <div class="col-md-6 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="Date" class="form-control" name="to_date">
                        </div>                        
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success" tyle="color: #fff;font-size:16px;font-weight: 800;"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>
    </div>        
</div>
@endsection
@push('js')
<script>
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
