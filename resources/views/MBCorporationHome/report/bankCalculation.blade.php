@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;margin-bottom: 500px;">
    <div class="row">

        <br>
        <br>
        <script lang='javascript'>
            function printData()
				{
				   var print_ = document.getElementById("main_table");
				//    win = window.open("");
				   window.document.write(print_.outerHTML);
				   window.print();
				//    window.close();
				}
        </script>
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <style type="text/css">
                .source_file_list {
                    height: 35px;
                    float: right;
                    background-color: #99A3A4;

                    padding: 5px;
                }

                .source_file_list a {
                    text-decoration: none;
                    padding: 5px 20px;
                    color: #fff;
                    font-size: 18px;

                }

                .source_file_list a:hover {
                    background-color: #D6DBDF;
                    color: #fff;
                }
            </style>
            <div class="source_file_list">
                <a style="color: #fff;" type="sumit" onclick="printData()">Print</a>
                <a href="#">PDF</a>
                <a href="#">Excal</a>
            </div>
        </div>
        <div class="col-md-12"  id="main_table">

            <br>
            <table class="table table-bordered" cellspacing="0"
                style="border: 1px solid rgb(53, 53, 53);text-align: center;width:100%">

                <td colspan="7" style="text-align: center;">
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
                            // dd($formDate);

                        @endphp

                        @foreach($company as $company_row)

                        <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                        <p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
                            {{$company_row->mobile_number}}</p>
                        @endforeach
                        <h4>Bank Interest Calculation </h4>

                        <p style="text-align: left;">Account : {{ $ledger->account_name }} </p>
                        <p style="text-align: left;">Account Address: {{ $ledger->account_ledger_address.' '.$ledger->account_ledger_phone }} </p>
                        <p style="text-align: right; padding-right: 20px;">From :
                            {{date('d-m-y', strtotime($formDate)).' To '.  date('d-m-y', strtotime($toDate))}}
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

                        // dd($datetime1,  $datetime2, $diffDays);

                    }
                    // $difference = $datetime1->diff($datetime2);
                    // $diffDays = $difference->d;

                    //
                    // elseif($i >= 1){
                    //     $datetime1 = new DateTime($account_tran[$i-1]->date);
                    //     $datetime2 = new DateTime($account_tran[$i]->date);
                    //     $difference = $datetime1->diff($datetime2);
                    //     $diffDays = $difference->d;
                    // }
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
</div>

@endsection
