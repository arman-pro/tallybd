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
                                    <input type="month" class="form-control" name="from_date" value="{{request()->from_date }}"
                                        required/>
                                </div>
                
                                <div class="form-group">
                                    <label for="cono1" class="control-label col-form-label">To</label>
                                    <input type="month" class="form-control" name="to_date" value="{{ request()->to_date }}"
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
                            <p style="margin:0"> From : {{ request()->from_date }} TO : {{ request()->to_date }}</p>
                            <h4 style="margin:0">All Receivable & Payable</h4>
                        </div>
            
                        <table cellspacing='0' class="table table-borderless" style="width: 100%"">
                            <thead>
                                <tr style=" font-size:14px;">
                            <th style="border: 1px solid black;padding: 5px 5px;text-align: center;font-weight: 800;width:50%">Receivable (TK)</th>
                            <th style="border: 1px solid black;padding: 5px 5px;text-align: center;font-weight: 800;width:50%">Payable (TK)</th>
                            </tr>
                            </thead>
                            <tbody>
                            
                            <tr style="font-size:14px;">
                                    <td style="border: 0px solid black;padding: 5px ">
                                        <table style="text-align: center;width: 100%">
                                            @forelse ($ledger as $item)
                                            <?php
                                                $amount = $item->received_payment_amount($endMonth);
                                            ?>
                                            @if($amount > 0)
                                            @php
                                            $leftSide += $amount??0;
                                            @endphp
                                            <tr style="font-size:14px;font-weight: 700;">
                                                <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                                    {{$item->account_name}}
                                                </td>
                                                <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                                    {{ new_number_format( $amount??0.00)}} (DR)
                                                </td>
                                            </tr>
            
                                            @endif
                                            @empty
            
                                            @endforelse
                                        </table>
                                    </td>
            
            
                                    <td style="border: 0px solid black;padding: 5px;">
                                        <table style="width: 100%">
                                            @forelse ($ledger as $payable_item)
                                            <?php
                                                $amount = $payable_item->received_payment_amount($endMonth);
                                            ?>
                                            @if($amount < 0) @php $rightSide +=($amount * (-1)) ?? 0;
                                                @endphp
                                                <tr style="font-size:14px;font-weight: 700;">
                                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                                        {{$payable_item->account_name}}
                                                    </td>
                                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                                        {{ new_number_format( $amount??0.00)}} (CR)
                                                    </td>
                                                </tr>
                                                @endif
                                                @empty
            
                                                @endforelse
            
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right;font-weight: 800;padding:0%">{{new_number_format($leftSide)}} (DR)
                                    </td>
                                    <td style="text-align: right;font-weight: 800;padding:0%"> {{new_number_format($rightSide)}} (CR)
                                    </td>
                                </tr>
                            </tbody>
            
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-lg btn-success text-white fw-bold" onclick="printData()"><i class="fa fa-print"></i> Print</button>
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
