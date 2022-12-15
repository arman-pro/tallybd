@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Receive Payment Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;*/
        /*height: 11.7in; */
        margin: 10px;
        padding: 0;
    }
    .content_area {
        /* width: 8.3in;
        height: 11.7in;
        margin: auto;
        border: 1px solid black;
        display: block; */
    }
    
    /*@page {*/
    /*    page: a4;*/
    /*    margin: 0.2in;*/
    /*}*/

    .pdf-table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }

    .pdf-table tr, .pdf-table  th, .pdf-table  td, .pdf-table thead {
        border: 1px solid black;
        padding: 5px 3px;
    }

    .text-center {
        text-align: center;
    }

    .float-end {
        float: right;
    }

    .float-start {
        float: left;
    }

    .page-break {
        page-break-after: always;
    }
</style>
@endpush

@section('pdf_content')
<div class="container-fluid">
    <?php
        $company = App\Companydetail::first(); 
    ?>
   
    <div class="p-0 content_area" >
        <div>
            <h3 style="font-weight: 800;margin:0;text-align:center;">{{$company->company_name}}</h3>
            <p style="margin:0;text-align:center;">{{$company->company_address}}<br> Call:
                {{$company->mobile_number}}</p>
            <h4 style="margin:0;text-align:center;">All Receive & Payments</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime($formdate))}} TO : {{date('d-m-Y', strtotime($todate))}} </strong></p>
            <table class="pdf-table">
                <tr style="font-size:16px;font-weight: 800;">
                    <td>Receive</td>
                    <td>Payment</td>
                </tr>
                </tr>

                <tr style="font-size:14px;">
                    <td>
                        <table class="pdf-table" style="text-align: center;">
                            <tr style="font-size:14px;font-weight: 700;">
                                <td style="text-align: left;">Date</td>
                                <td style="text-align: left;">Account Ledger</td>
                                <td style="text-align: right;">Amount(TK)</td>
                            </tr>
                            @php
                            $total_rec = 0;
                            $Receive = App\Receive::whereBetween('date',[$formdate,$todate])->get();
                            @endphp
                            @foreach($Receive as $Receive_row)
                            <tr style="font-size:14px;">
                                <td style="text-align: left;">{{ date('d-m-Y', strtotime($Receive_row->date)) }}</td>
                                <td style="text-align: left;">
                                    @php
                                    $total_rec += $Receive_row->amount;
                                    $account_name =
                                    App\AccountLedger::where('id',$Receive_row->account_name_ledger_id)->first();
                                    @endphp
                                    {{$account_name->account_name}}
                                </td>
                                <td style="text-align: right;">{{new_number_format($Receive_row->amount)}}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td >
                        <table class="pdf-table" style="text-align: center;">
                            <tr style="font-size:14px;font-weight: 700;">
                                <td style="text-align: left;">Date</td>
                                <td style="text-align: left;">Account Ledger</td>
                                <td style="text-align: right;">Amount(TK)</td>
                            </tr>
                            @php
                            $total_pay = 0;
                            $Receive = App\Payment::whereBetween('date',[$formdate,$todate])->get();
                            @endphp
                            @foreach($Receive as $Receive_row)
                            <tr style="font-size:14px;">
                                <td style="text-align: left;">{{ date('d-m-Y', strtotime($Receive_row->date)) }}</td>
                                <td style="text-align: left;">
                                    @php
                                    $total_pay = $total_pay + $Receive_row->amount;

                                    $account_name =
                                    App\AccountLedger::where('id',$Receive_row->account_name_ledger_id)->first();
                                    @endphp
                                    {{$account_name->account_name}}
                                </td>
                                <td style="text-align: right;">{{$Receive_row->amount}}.00
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>

                <tr style="font-size:16px;font-weight: 800;">
                    <td style="text-align: right;">Total :
                        {{$total_rec}}.00</td>
                    <td style="text-align: right;">Total :
                        {{$total_pay}}.00
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</div>

@endsection