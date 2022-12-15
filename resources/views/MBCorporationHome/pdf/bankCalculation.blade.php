@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Bank Calculation Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;
        height: 11.7in; */
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

    <div class="p-0 content_area" >
        <table id="printArea" class="table table-bordered" cellspacing="0"
                    style="border: 1px solid rgb(53, 53, 53);text-align: center;width:100%">
            <tr>
            <td colspan="5" style="text-align: center;">
                    @php
                        $company = App\Companydetail::get();
                        $openDr = 0;
                        $openCr = 0;
                        $openBalance = 0;
                        $opening = App\AccountLedgerTransaction::where('ledger_id',$ledger_id)->where('date', '<' ,$formDate
                            )->get();
                            if($opening){
                            $openDr = $opening->sum('debit');
                            $openCr = $opening->sum('credit');
                            $openBalance = $openDr - $openCr;
                            }

                    @endphp

                    @foreach($company as $company_row)

                    <h3 style="margin:0;">{{$company_row->company_name}}</h3>
                    <p style="margin:0;">{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
                        {{$company_row->mobile_number}}</p>
                    @endforeach
                    <h4 style="margin:0;">Bank Interest Calculation </h4>    
                    <p style="text-align: left; margin:0;">Account : {{ $ledger->account_name }} </p>
                    <p style="text-align: left; margin:0;" class="clearfix">
                        <span class="float-start">
                            Account Address: {{ $ledger->account_ledger_address.' '.$ledger->account_ledger_phone }}
                        </span> 
                        <span class="float-end">
                            <b>From :</b> {{date('d-m-y', strtotime($formDate))}} <b>To : <b/> {{date('d-m-y', strtotime($toDate))}}
                        </span>
                    </p>
            </td>
            </tr>

            <tr style="font-size:14px;font-weight: 800;">
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 20%;">Balance Of Date</td>
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 10%;">Date</td>
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 5%;"> Days Diff</td>
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 20%;text-align: center;">Interest Balance ({{$percent}}%) </td>
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 20%;text-align: center;">Total Balance </td>
            </tr>


            @php
            $i=0;
            $x = 0;
            $dr = 0;
            $cr = 0;
            $newBalance = 0;
            $percentBalance = $totalBalance = 0;
            @endphp
            <tr style="font-size:14px;">
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 100px;">
                    @if($openBalance > 1)
                    {{ number_format($openBalance, 2)}} (Dr)
                    @elseif($openBalance < 1)
                        {{ number_format($openBalance*-1, 2)}} (Cr)
                    @else
                       0.00
                    @endif

                </td>

                <td style="border: 1px solid rgb(53, 53, 53);"> {{date('d-m-y', strtotime($formDate))}}</td>
                <td style="border: 1px solid rgb(53, 53, 53);">0</td>
                <td style="border: 1px solid rgb(53, 53, 53);">0.00</td>
                <td style="border: 1px solid rgb(53, 53, 53);">&nbsp;</td>
            </tr>
            @for ($i = 0; $i < count($account_tran); $i++)


            @php
                if($openBalance > 0 && $i == 0){
                    $dr+=$openBalance;
                }elseif($openBalance < 0 && $i == 0){
                    $cr+=$openBalance;
                }
                if($account_tran[$i]['amount'] > 0){
                    $dr+=$account_tran[$i]['amount']??0;
                }else{
                    $cr+=$account_tran[$i]['amount']??0;
                }
                $newBalance = $dr- $cr;
                $diffDays = 0;
                if($i == 0){
                    $datetime1 = new DateTime($formDate);
                    $datetime2 = new DateTime($account_tran[$i+1]->date);
                    $difference = $datetime1->diff($datetime2);
                $diffDays = $difference->d;
                }elseif($i < count($account_tran)-1){
                    // $datetime1 = new DateTime($formDate);
                    $datetime1= new DateTime($account_tran[$i]->date);
                    $datetime2 = new DateTime($account_tran[$i+1]->date);
                    $difference = $datetime1->diff($datetime2);
                    $diffDays = $difference->d;
                }
                
                $percentBalance =  ((($percent/100) * $newBalance)/$bankDays) * $diffDays;
                $totalBalance +=  ((($percent/100) * $newBalance)/$bankDays)* $diffDays;
            @endphp
            <tr style="font-size:14px;">
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 100px;">
                    @if($newBalance > 1 )
                    {{ number_format($newBalance, 2)." ("."DR)"}}
                    @else
                    {{number_format($newBalance*-1, 2)." ("."CR)"}}
                    @endif

                </td>
                <td style="border: 1px solid rgb(53, 53, 53);">  {{date('d-m-y', strtotime($account_tran[$i]->date))??'-' }}</td>

                <td style="border: 1px solid rgb(53, 53, 53);">{{$diffDays??0}}</td>
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 150px;text-align: center;">
                    {{number_format($percentBalance, 2) }}
                </td>
                <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 150px;text-align: center;">
                    {{number_format($totalBalance, 2) }}
                </td>

            </tr>
            @endfor
        </table>
    </div>
</div>

@endsection