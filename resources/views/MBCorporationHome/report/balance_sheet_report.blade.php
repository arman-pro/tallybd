@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    {{-- <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #rgb(17, 17, 17);">Balance
        Sheet</h3>
    <h4 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #rgb(17, 17, 17);">From : {{
        date('m-d-Y', strtotime($settingDate->financial_year_from ) ) }} To: {{ date('m-d-Y',
        strtotime($settingDate->financial_year_to ) ) }}</h4> --}}
    <div class="row">
        <br>
        <br>
        <script lang='javascript'>
            function printData()
                {
                var divToPrint = document.getElementById('main_table');
                var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'table th, table td {'
                    +'border:1px solid #000;'
                    +
                    '}' +
                    '</style>';
                    htmlToPrint += divToPrint.outerHTML;
                    // newWin = window.open("");
                    window.document.write(htmlToPrint);
                    window.print();
                    // window.close();
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

                .table td,
                .table th {
                    padding: 0.5rem;
                }

                table tbody,
                td,
                th,
                thead,
                tr {
                    border: 1px solid;
                    border-color: black !important;
                }

                @media print {
                    @page {
                        size: A4;
                        /* DIN A4 standard, Europe */
                        margin: 0%;
                        padding: 0%;
                        width: 100%
                    }

                    html,
                    body {
                        /* this affects the margin on the content before sending to printer */
                        margin: 0%;
                        padding: 0%;
                        width: 100%
                    }

                    table tbody,
                    td,
                    th,
                    thead,
                    tr {
                        border: 1px solid !important;
                        border-color: black !important;
                    }
                }
            </style>
            @if(request()->to_date)
             <a href="{{route('balance_sheet_report')}}" class="btn btn-sm btn-danger">Clear</a>
            @endif
            <button type="button" class="btn btn-sm btn-danger" id="filter_btn">Filter</button>
            <div class="source_file_list">
                <a style="color: #fff;" type="sumit" onclick="printData()">Print</a>
            </div>
        </div>
        <form method="GET" action="{{route('balance_sheet_report')}}" id="filter_form">
        <div class="row">
            <div class="col-md-6 col-sm-12 px-3">
                <div class="form-group">
                    <label for="from_date">From Date</label>
                    <input type="date" class="form-control" value="2021-01-01" name="from_date" id="from_date" readonly />
                </div>
            </div>
            <div class="col-md-6 col-sm-12 px-3">
                <div class="form-group">
                    <label for="to_date">To Date</label>
                    <input type="date" class="form-control" name="to_date" id="to_date" />
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-sm btn-success" style="float:right;">Search</button>
                </div>
            </div>
        </div>
        </form>
        <section class="col-md-12" id="main_table">
            <table class="table" cellspacing="0"  style="text-align: center;width:100%">
                <thead>
                    <tr>
                        <th  style="text-align: center;">
                            @php
                            $from = null;
                            $to = null;
                            if(request()->form_date && request()->to_date){
                            $from = date('Y-m-d', strtotime(request()->form_date));
                            $to = date('Y-m-d', strtotime(request()->to_date));
                            }
                            $company = App\Companydetail::first();

                            @endphp


                            <h3 style="font-weight: 800;margin:0">{{$company->company_name}}</h3>
                            <strong style=>{{$company->company_address}}, Tel: {{$company->phone}}, Call:
                                {{$company->mobile_number}}</strong><br>
                            <strong>Balance Sheet</strong><br>
                            <strong>From : {{$from}} TO : {{ $to }} </strong>
                        </th>
                    </tr>
                </thead>
            </table>
            <div>

                <div style="width: 49.5%;float:left;">
                    <table class="table table-bordered" cellspacing="0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="background-color:#97959582; text-align: center">Asset </th>
                                <th style=" background-color:#97959582 ;text-align: center">Total</th>
                            </tr>


                        </thead>
                        <tbody>


                            @php
                            $asset_grand_total = 0;
                            $Liabilities_group = App\AccountGroup::where('account_group_nature', 'Assets')->where('account_group_under_id', NULL)->get();
                            @endphp
                            @foreach($Liabilities_group as $lg)
                            @php
                                $AccountLedger = App\AccountLedger::where('account_group_id', $lg->id )->get();
                                $ledgertotal=0;
                                foreach($AccountLedger as $aL){
                                    $LedgerSummary = App\LedgerSummary::where('ledger_id', $aL->id )->first();
                                    $ledgertotal +=$LedgerSummary->grand_total;
                                }
                                $asset_grand_total +=$ledgertotal;
                                // account_group_under_id
                                
                                $Liabilities_group_under = App\AccountGroup::where('account_group_under_id', $lg->id)->get();
                                
                                foreach($Liabilities_group_under as $lgu){
                                   
                                    $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                    $ledgertotal_u=0;
                                    foreach($AccountLedger_u as $aLu){
                                        $LedgerSummaryu = App\LedgerSummary::where('ledger_id', $aLu->id )->first();
                                        $ledgertotal_u += $LedgerSummaryu->grand_total;
                                    }
                                    $asset_grand_total +=$ledgertotal_u;
                                    $ledgertotal +=$ledgertotal_u;
                                }
                                
                                
                                   
                            @endphp    
                                <tr>
                                    <td style="text-align:left"><b>{{  $lg->account_group_name  }}</b></td>
                                    <td style="text-align:right"><b>
                                        @if ( $ledgertotal  > 1)
                                        {{ new_number_format($ledgertotal??0, 2) }}(DR)
                                        @else
                                        {{ new_number_format($ledgertotal *-1 ??0, 2) }}(CR)
                                        @endif
                                    </b></td>
                                </tr>
                                
                                    @foreach($Liabilities_group_under as $lgu)
                                        @php
                                            $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                            $ledgertotal=0;
                                            foreach($AccountLedger_u as $aLu){
                                                $LedgerSummaryu = App\LedgerSummary::where('ledger_id', $aLu->id )->first();
                                                $ledgertotal += $LedgerSummaryu->grand_total;
                                            }
                                            
                                        @endphp
                                        <tr>
                                            <td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{  $lgu->account_group_name  }}</td>
                                            <td style="text-align:left">
                                                @if ( $ledgertotal  > 1)
                                                {{ new_number_format($ledgertotal??0, 2) }}(DR)
                                                @else
                                                {{ new_number_format($ledgertotal *-1 ??0, 2) }}(CR)
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                
                            @endforeach

                            <tr>
                                <td style=" text-align:left;padding:1px"><strong>Stock Value</strong> </td>
                                <td style="text-align:right;"><b>{{ new_number_format($stockValue??0, 2) }}</b></td>
                            </tr>
                            <tr>
                                <td style=" text-align:left;padding:1px"><strong>Profit/Loss</strong> </td>
                                @if($getProfit['profit']  > 0)
                                <td style="text-align:right;"><b>{{ new_number_format($getProfit['profit']??0, 2) }} (DR)</b></td>
                                @else
                                <td style="text-align:right;"><b>- {{ new_number_format($getProfit['loss']??0, 2) }} (CR)</b></td>

                                @endif

                            </tr>


                            @if($assets)
                            <tr style="background: rgb(91, 165, 91);font-size:16pssx">
                                <td> Assets (Total)</td>
                                <td style="border-top: 1px solid #97959582; text-align:right">
                                    @if ($asset_grand_total + $getProfit['profit'] + $stockValue - $getProfit['loss'] > 1)
                                    
                                    {{ new_number_format($asset_grand_total + $getProfit['profit'] + $stockValue - $getProfit['loss'], 2) }} (Dr)
                                    @else
                                    {{ new_number_format(($asset_grand_total + $getProfit['profit'] + $stockValue - $getProfit['loss'])*-1, 2) }} (Cr)
                                    @endif
                                </td>
                            </tr>
                            @endif


                        </tbody>
                    </table>
                </div>

                <div style="width: 50%;float:left;margin-left:0.5%">
                    <table class="table table-bordered" cellspacing="0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="background-color:#97959582; text-align: center;font-">Liabilities</th>
                                <th style="background-color:#97959582; text-align: center;">Total</th>
                            </tr>

                        </thead>
                        <tbody>

                            @php
                            $liability_grand_total = 0;
                            $Liabilities_group = App\AccountGroup::where('account_group_nature', 'Liabilities')->where('account_group_under_id', NULL)->get();
                            @endphp
                            @foreach($Liabilities_group as $lg)
                            @php
                                $AccountLedger = App\AccountLedger::where('account_group_id', $lg->id )->get();
                                $ledgertotal=0;
                                foreach($AccountLedger as $aL){
                                    $LedgerSummary = App\LedgerSummary::where('ledger_id', $aL->id )->first();
                                    $ledgertotal +=$LedgerSummary->grand_total;
                                }
                                $liability_grand_total +=$ledgertotal;
                                // account_group_under_id
                                
                                $Liabilities_group_under = App\AccountGroup::where('account_group_under_id', $lg->id)->get();
                                
                                foreach($Liabilities_group_under as $lgu){
                                   
                                    $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                    $ledgertotal_u=0;
                                    foreach($AccountLedger_u as $aLu){
                                        $LedgerSummaryu = App\LedgerSummary::where('ledger_id', $aLu->id )->first();
                                        $ledgertotal_u += $LedgerSummaryu->grand_total;
                                    }
                                    $liability_grand_total +=$ledgertotal_u;
                                    $ledgertotal +=$ledgertotal_u;
                                }
                                
                                
                                   
                            @endphp    
                                <tr>
                                    <td style="text-align:left"><b>{{  $lg->account_group_name  }}</b></td>
                                    <td style="text-align:right"><b>
                                        @if ( $ledgertotal  > 1)
                                        {{ new_number_format($ledgertotal??0, 2) }}(DR)
                                        @else
                                        {{ new_number_format($ledgertotal *-1 ??0, 2) }}(CR)
                                        @endif
                                    </b></td>
                                </tr>
                                
                                    @foreach($Liabilities_group_under as $lgu)
                                        @php
                                            $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                            $ledgertotal=0;
                                            foreach($AccountLedger_u as $aLu){
                                                $LedgerSummaryu = App\LedgerSummary::where('ledger_id', $aLu->id )->first();
                                                $ledgertotal += $LedgerSummaryu->grand_total;
                                            }
                                            
                                        @endphp
                                        <tr>
                                            <td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{  $lgu->account_group_name  }}</td>
                                            <td style="text-align:left">
                                                @if ( $ledgertotal  > 1)
                                                {{ new_number_format($ledgertotal??0, 2) }}(DR)
                                                @else
                                                {{ new_number_format($ledgertotal *-1 ??0, 2) }}(CR)
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                
                            @endforeach
                            
                           

                            @if($liabilities)
                            <tr style="background-color: rgb(207, 107, 107); font-size:16px">
                                <td>Liabilities (Total)</td>
                                <td style="border-top: 1px solid #97959582;text-align:right">
                                    @if ($liability_grand_total> 1)
                                    {{ new_number_format($liability_grand_total, 2) }} (Dr)
                                    @else
                                    {{ new_number_format(($liability_grand_total)*-1, 2) }} (Cr)
                                    @endif

                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                @php
                $difference = 0;
                $rightSide = $liability_grand_total ;
                $leftSide = $asset_grand_total + $getProfit['profit']+ $stockValue -$getProfit['loss'];

                if($rightSide > 1 && $leftSide >1){
                $difference= $leftSide - $rightSide;
                }elseif($rightSide < 1 && $leftSide <1){
                    $difference=$rightSide + $leftSide;
                }
                elseif($rightSide> 1 && $leftSide < 1)
                { $difference=$rightSide + $leftSide; }elseif($rightSide < 1 && $leftSide> 1){
                    $difference= $leftSide + $rightSide;
                }
                @endphp


            </div>


        </section>



    </div>
</div>

@endsection
@push('js')
<script>
    $(document).ready(function(){
        $('#filter_form').hide();
        $('#filter_btn').on('click', function(){
            $('#filter_form').toggle('slow');
        });
    });
</script>
@endpush

