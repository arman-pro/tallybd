@extends('MBCorporationHome.apps_layout.layout')
@section("title", "All Receive & Payment Report")

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

    @php
    $company = App\Companydetail::first();
    $leftSide =0;
    $rightSide =0;
    @endphp
    <div class="card">
        <div class="card-header bg-success text-light">
            <h4 class="card-title">All Receive & Payment Report</h4>
        </div>
        <div class="card-body">
            <table class="table" id="printArea" style="border: 1px solid #eee;text-align: center;width: 100%;">
                <tr style="border: 1px solid #eee;">
                    <td colspan="2" style="text-align: center;">
                        @php
                        $company = App\Companydetail::get();
                        @endphp

                        @foreach($company as $company_row)

                        <h3 style="margin:0;">{{$company_row->company_name}}</h3>
                        <p style="margin:0;">{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
                            {{$company_row->mobile_number}}</p>
                        @endforeach
                        <p style="margin:0;">{{date('d-m-Y', strtotime($formdate))}} To {{date('d-m-Y', strtotime($todate))}}</p>
                        <h4 style="margin:0;">All Receive & Payments</h4>
                    </td>
                </tr>
                <tr style="font-size:16px;font-weight: 800;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Receive</td>
                    <td style="padding: 5px 5px;width: 100px;">Payment</td>
                </tr>
                </tr>

                <tr style="font-size:14px;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                        <table style="text-align: center;width: 100%">
                            <tr style="font-size:14px;font-weight: 700;">
                                <td style="padding: 5px 5px;width: 20%;text-align: left;">Date</td>
                                <td style="padding: 5px 5px;width: 60%;text-align: left;">Account Ledger</td>
                                <td style="padding: 5px 5px;width: 40%;text-align: right;">Amount(TK)</td>
                            </tr>
                            @php
                            $total_rec = 0;
                            $Receive = App\Receive::whereBetween('date',[$formdate,$todate])->get();
                            @endphp
                            @foreach($Receive as $Receive_row)
                            <tr style="font-size:14px;">
                                <td style="padding: 5px 5px;width: 20%;text-align: left;">{{ date('d-m-Y', strtotime($Receive_row->date)) }}</td>
                                
                                <td style="padding: 5px 5px;width: 50%;text-align: left;">
                                    @php
                                    $total_rec += $Receive_row->amount;
                                    $account_name =
                                    App\AccountLedger::where('id',$Receive_row->account_name_ledger_id)->first();
                                    @endphp
                                    {{$account_name->account_name}}
                                </td>
                                <td style="padding: 5px 5px;width: 30%;text-align: right;">{{new_number_format($Receive_row->amount)}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td style="padding: 5px 5px;width: 100px;">
                        <table style="width: 100%">
                            <tr style="font-size:14px;font-weight: 700;">
                                <td style="padding: 5px 5px;width: 20%;text-align: left;">Date</td>
                                <td style="padding: 5px 5px;width: 60%;text-align: left;">Account Ledger</td>
                                <td style="padding: 5px 5px;width: 50%;text-align: right;">Amount(TK)</td>
                            </tr>
                            @php
                            $total_pay = 0;
                            $Receive = App\Payment::whereBetween('date',[$formdate,$todate])->get();
                            @endphp
                            @foreach($Receive as $Receive_row)
                            <tr style="font-size:14px;">
                                <td style="padding: 5px 5px;width: 20%;text-align: left;">{{ date('d-m-Y', strtotime($Receive_row->date)) }}</td>
                                <td style="padding: 5px 5px;width: 50%;text-align: left;">
                                    @php
                                    $total_pay = $total_pay + $Receive_row->amount;

                                    $account_name =
                                    App\AccountLedger::where('id',$Receive_row->account_name_ledger_id)->first();
                                    @endphp
                                    {{$account_name->account_name}}
                                </td>
                                <td style="padding: 5px 5px;width: 30%;text-align: right;">{{$Receive_row->amount}}.00
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>

                <tr style="font-size:16px;font-weight: 800;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;text-align: right;">Total :
                        {{$total_rec}}.00</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;text-align: right;">Total :
                        {{$total_pay}}.00
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="card-footer text-center">
            <button class="btn btn-lg btn-success text-light fw-bold" onclick="printData()"><i class="fa fa-print"></i> Print</button>
            <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
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
