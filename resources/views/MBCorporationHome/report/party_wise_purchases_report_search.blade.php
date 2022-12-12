@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Party Wise Purchase Report")

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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary fw-bold" href="{{route('all_purchases_report')}}">All Purchase</a>
                    <a class="btn btn-success text-light fw-bold" href="{{route('item_wise_purchases_report_search_form')}}">Item Wise Purchase</a>
                    <a class="btn btn-danger fw-bold" href="{{route('party_wise_purchases_report_search')}}">Party Wise Purchase </a>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <form action="{{url('/party_wise_purchases_report')}}" method="POST">
                @csrf
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Party Wise Purchase Report</h4>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">Party Name</label>
                            <select 
                                class="form-control" name="account_ledger_id" id="account_ledger_id"
                                data-placeholder="Select a Party Name"
                            >
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
                            <label for="cono1" class="control-label col-form-label">Form</label>
                            <input type="Date" class="form-control" name="form_date" required/>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="Date" class="form-control" name="to_date" required />
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success btn-lg fw-bold text-light"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    
    $(document).ready(function(){
        $('#account_ledger_id').select2();
    });
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
