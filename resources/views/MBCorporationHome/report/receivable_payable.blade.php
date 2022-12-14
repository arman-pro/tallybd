@extends('MBCorporationHome.apps_layout.layout')
@section("title", "All Receive & All Payment")

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
            <form action="{{url('/all-receivable-payable')}}" method="GET">
                <input type="hidden" name="report" value="1">
                <div class="card">
                    <div class="card-header bg-success text-light">
                        <h4 class="card-title">All Receivable & All Payable Search</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 m-auto col-sm-12">
                                <div class="form-group">
                                    <label for="cono1" class="control-label col-form-label">From</label>
                                    <input type="month" class="form-control" name="from_month" value="{{request()->from_date }}"
                                        required/>
                                </div>
                
                                <div class="form-group">
                                    <label for="cono1" class="control-label col-form-label">To</label>
                                    <input type="month" class="form-control" name="to_month" value="{{ request()->to_date }}"
                                            required>
                                </div>    
                            </div>                            
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success btn-lg text-white fw-bold"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>                
            </form>
        </div>

        @if(request()->report)
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">All Receivable & All Payable Report</h4>
                </div>
                <div class="card-body">
                    @php
                    $company = App\Companydetail::first();
                    $leftSide =0;
                    $rightSide =0;
                    @endphp
                    <div id="printArea" class="display">           
            
                        <div style="text-align:center">
                            <h3 style="font-weight: 800;margin:0">{{$company->company_name}}</h3>
                            <p style="margin:0">{{$company->company_address}}<br> {{$company->phone}} Call:
                                {{$company->mobile_number}}</p>
                            <p style="margin:0"> From : {{ request()->from_month }} TO : {{ request()->to_month }}</p>
                            <h4 style="margin:0">All Receivable & Payable</h4>
                        </div>
                        
                        <div class="row" style="display:flex;">
                            <div class="col-md-6 col-sm-12" style="width:50%;padding:5px;">
                                <table style="text-align: center;width: 100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2" style="text-align:center;width:100%;">Receivable (TK)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($ledger as $item)
                                    <?php
                                        $amount = $item->received_payment_amount($endMonth);
                                    ?>
                                    @if($amount > 0)
                                    @php
                                        $leftSide += $amount??0;
                                    @endphp
                                    <tr style="font-size:14px;font-weight: 700;">
                                        <td style="padding: 5px 5px;text-align: left;">
                                            {{$item->account_name}}
                                        </td>
                                        <td style="padding: 5px 5px;text-align: right;">
                                            {{ new_number_format( $amount??0.00)}} (DR)
                                        </td>
                                    </tr>
    
                                    @endif
                                    @empty
    
                                    @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th style="text-align:right;">{{new_number_format($leftSide)}} (DR)</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-6 col-sm-12" style="width:50%;padding:5px;">
                                <table style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th colspan="2" style="text-align:center;width:100%;">Payable (TK)</th>
                                        </tr>
                                    </thead>
                                    @forelse ($ledger as $payable_item)
                                    <?php
                                        $amount = $payable_item->received_payment_amount($endMonth);
                                    ?>
                                    @if($amount < 0) @php $rightSide +=($amount * (-1)) ?? 0;
                                        @endphp
                                        <tr style="font-size:14px;font-weight: 700;">
                                            <td style="padding: 5px 5px;text-align: left;">
                                                {{$payable_item->account_name}}
                                            </td>
                                            <td style="padding: 5px 5px;text-align: right;">
                                                {{ new_number_format( $amount??0.00)}} (CR)
                                            </td>
                                        </tr>
                                        @endif
                                        @empty
                                        
                                    @endforelse
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th style="text-align:right;">{{new_number_format($rightSide)}} (CR)</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
            
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-lg btn-success text-white fw-bold" onclick="printData()"><i class="fa fa-print"></i> Print</button>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
                    <a href="{{url()->full()}}&excel=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
            </div>           
        </div>
        @endif
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
