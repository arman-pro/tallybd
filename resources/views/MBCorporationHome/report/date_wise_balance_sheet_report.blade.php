@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Balance Sheet Report")

@push('css')
<style type="text/css">

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
@endpush

@section('admin_content')
<div class="container-fluid">
   
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(request()->to_date)
                     <a href="{{route('balance_sheet_report')}}" class="btn btn-lg btn-warning fw-bold">Clear</a>
                    @endif
                    <button type="button" class="btn btn-lg btn-danger fw-bold" id="filter_btn">Filter</button>
                    <button class="btn btn-lg btn-success text-light fw-bold" onclick="printData()"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
            
            <form method="GET" action="{{route('balance_sheet_report')}}">
            <div class="card mt-2" id="filter_form">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Filter By Date</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 px-3">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="date" class="form-control" name="from_date" id="from_date" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 px-3">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="date" class="form-control" name="to_date" id="to_date" />
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-lg btn-success fw-bold text-light"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>
        
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Balance Sheet Report</h4>
                </div>
                <div class="card-body" id="main_table">
                    <table class="table" cellspacing="0"  style="text-align: center;width:100%">
                <thead>
                    <tr>
                        <th  style="text-align: center;">
                            @php
                            $from = null;
                            $to = null;
                            if(request()->from_date && request()->to_date){
                                $from = date('Y-m-d', strtotime($date['from_date']));
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
                            <?php
                                function recursion_group($id, $total) 
                                {
                                    $account_under_groups = App\AccountGroup::where('account_group_under_id', $id)->get();
                                    if($account_under_groups->isEmpty()) {
                                        return $total;
                                    }
                                    
                                    foreach($account_under_groups as $account_under_group) {                                                
                                        $account_ledger_under_groups = App\AccountLedger::where('account_group_id', $account_under_group->id )->get();
                                        foreach($account_ledger_under_groups as $account_ledger_under_group) {
                                    
                                            $LedgerSummary = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                            ->where('ledger_id', $account_ledger_under_group->id)
                                            ->whereDate('date', '>=', request()->from_date)
                                            ->whereDate('date', '<=', request()->to_date)
                                            ->groupBy('account_ledger__transaction_id')
                                            ->get();
                                            $total += ($LedgerSummary->sum("debit") ?? 0) - ($LedgerSummary->sum('credit') ?? 0);
                                        }

                                        $total = recursion_group($account_under_group->id, $total);
                                    }
                                    return $total;
                                }
                                $asset_grand_total = 0;
                                $Liabilities_group = App\AccountGroup::where('account_group_nature', 'Assets')->where('account_group_under_id', NULL)->get();
                            ?>
                            @foreach($Liabilities_group as $lg)
                            <?php
                                $AccountLedger = App\AccountLedger::where('account_group_id', $lg->id)->get();
                                $ledgertotal=0;
                                foreach($AccountLedger as $aL){
                                   
                                    $LedgerSummary = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                    ->where('ledger_id', $aL->id)
                                    ->whereDate('date', '>=', request()->from_date)
                                    ->whereDate('date', '<=', request()->to_date)
                                    ->groupBy('account_ledger__transaction_id')
                                    ->get();
                                   // ->unique('account_ledger__transaction_id');
                                    
                                    $ledgertotal += $LedgerSummary->sum('debit') - $LedgerSummary->sum('credit');
                                }
                                $asset_grand_total +=$ledgertotal;
                                // account_group_under_id
                                
                                $Liabilities_group_under = App\AccountGroup::where('account_group_under_id', $lg->id)->get();
                                
                                foreach($Liabilities_group_under as $lgu){
                                   
                                    $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                    $ledgertotal_u=0;
                                    foreach($AccountLedger_u as $aLu){
                                        $LedgerSummaryu = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                        ->where('ledger_id', $aLu->id)
                                        ->whereDate('date', '>=', request()->from_date)
                                        ->whereDate('date', '<=', request()->to_date)
                                        ->groupBy("account_ledger__transaction_id")
                                        ->get();
                                        //->unique('account_ledger__transaction_id');
                                            
                                        $ledgertotal_u += $LedgerSummaryu->sum('debit') - $LedgerSummaryu->sum('credit');
                                    }
                                    $ledgertotal_u += recursion_group($lgu->id, 0);
                                    $asset_grand_total +=$ledgertotal_u;
                                    $ledgertotal +=$ledgertotal_u;
                                }
                                
                                
                                   
                            ?>    
                                <tr>
                                    <td style="text-align:left"><b>{{  $lg->account_group_name  }}</b></td>
                                    <td style="text-align:right"><b>
                                        @if ( $ledgertotal  > 1)
                                        {{ new_number_format($ledgertotal??0) }}(DR)
                                        @else
                                        {{ new_number_format($ledgertotal *-1 ??0) }}(CR)
                                        @endif
                                    </b></td>
                                </tr>
                                
                                    @foreach($Liabilities_group_under as $lgu)
                                        <?php
                                            $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                         
                                            $ledgertotal=0;
                                            foreach($AccountLedger_u as $aLu){
                                           
                                            $LedgerSummaryu = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                                ->where('ledger_id', $aLu->id)
                                                ->whereDate('date', '>=', request()->from_date)
                                                ->whereDate('date', '<=', request()->to_date)
                                                ->groupBy('account_ledger__transaction_id')
                                                ->get();
                                                //->unique('account_ledger__transaction_id');
                                            
                                                $ledgertotal += $LedgerSummaryu->sum('debit') - $LedgerSummaryu->sum('credit');
                                            
                                            }
                                            
                                            $ledgertotal += recursion_group($lgu->id, 0);
                                            
                                        ?>
                                        <tr>
                                            <td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{  $lgu->account_group_name  }}</td>
                                            <td style="text-align:left">
                                                @if ( $ledgertotal  > 1)
                                                {{ new_number_format($ledgertotal??0) }}(DR)
                                                @else
                                                {{ new_number_format($ledgertotal *-1 ??0) }}(CR)
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                
                            @endforeach

                            <tr>
                                <td style=" text-align:left;padding:1px"><strong>Stock Value</strong> </td>
                                <td style="text-align:right;"><b>{{ new_number_format($stockValue??0) }}</b></td>
                            </tr>
                            <tr>
                                <td style=" text-align:left;padding:1px"><strong>Profit/Loss</strong> </td>
                                @if($getProfit['profit']  > 0)
                                <td style="text-align:right;"><b>{{ new_number_format($getProfit['profit']??0) }} (DR)</b></td>
                                @else
                                <td style="text-align:right;"><b>- {{ new_number_format($getProfit['loss']??0) }} (CR)</b></td>

                                @endif

                            </tr>


                            @if($assets)
                            <tr style="background: rgb(91, 165, 91);font-size:16pssx">
                                <td> Assets (Total)</td>
                                <td style="border-top: 1px solid #97959582; text-align:right">
                                    @if ($asset_grand_total + $getProfit['profit'] + $stockValue - $getProfit['loss'] > 1)
                                    
                                    {{ new_number_format($asset_grand_total + $getProfit['profit'] + $stockValue - $getProfit['loss']) }} (Dr)
                                    @else
                                    {{ new_number_format(($asset_grand_total + $getProfit['profit'] + $stockValue - $getProfit['loss'])*-1) }} (Cr)
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

                            <?php
                            $liability_grand_total = 0;
                            $Liabilities_group = App\AccountGroup::where('account_group_nature', 'Liabilities')->where('account_group_under_id', NULL)->get();
                            ?>
                            @foreach($Liabilities_group as $lg)
                            <?php
                                $AccountLedger = App\AccountLedger::where('account_group_id', $lg->id )->get();
                                $ledgertotal=0;
                                foreach($AccountLedger as $aL){
                                    $LedgerSummary = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                    ->where('ledger_id', $aL->id )
                                    ->whereDate('date', '>=', request()->from_date)
                                    ->whereDate('date', '<=', request()->to_date)
                                    ->groupBy('account_ledger__transaction_id')
                                    ->get();
                                    //->unique('account_ledger__transaction_id');
                                    
                                    $ledgertotal += $LedgerSummary->sum('debit') - $LedgerSummary->sum('credit');
                                }
                                $liability_grand_total +=$ledgertotal;
                                // account_group_under_id
                                
                                $Liabilities_group_under = App\AccountGroup::where('account_group_under_id', $lg->id)->get();
                                
                                foreach($Liabilities_group_under as $lgu){
                                   
                                    $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                    $ledgertotal_u=0;
                                    foreach($AccountLedger_u as $aLu){
                                        $LedgerSummaryu = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                        ->where('ledger_id', $aLu->id)
                                        ->whereDate('date', '>=', request()->from_date)
                                        ->whereDate('date', '<=', request()->to_date)
                                        ->groupBy('account_ledger__transaction_id')
                                        ->get();
                                        //->unique('account_ledger__transaction_id');
                                        
                                        $ledgertotal_u += $LedgerSummaryu->sum('debit') - $LedgerSummaryu->sum('credit');
                                    }
                                    $ledgertotal_u += recursion_group($lgu->id, 0);
                                    $liability_grand_total +=$ledgertotal_u;
                                    $ledgertotal +=$ledgertotal_u;
                                }
                                   
                            ?>    
                                <tr>
                                    <td style="text-align:left"><b>{{  $lg->account_group_name  }}</b></td>
                                    <td style="text-align:right"><b>
                                        @if ( $ledgertotal  > 1)
                                        {{ new_number_format($ledgertotal??0) }}(DR)
                                        @else
                                        {{ new_number_format($ledgertotal *-1 ??0) }}(CR)
                                        @endif
                                    </b></td>
                                </tr>
                                
                                    @foreach($Liabilities_group_under as $lgu)
                                        <?php
                                            $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                            $ledgertotal=0;
                                            foreach($AccountLedger_u as $aLu){
                                                $LedgerSummaryu = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) credit')
                                                ->whereDate('date', '>=', request()->from_date)
                                                ->whereDate('date', '<=', request()->to_date)
                                                ->where('ledger_id', $aLu->id )
                                                ->groupBy('account_ledger__transaction_id')
                                                ->get();
                                                //->unique('account_ledger__transaction_id');
                                                
                                                $ledgertotal += $LedgerSummaryu->sum('debit') - $LedgerSummaryu->sum('credit');
                                            }
                                            $ledgertotal += recursion_group($lgu->id, 0);
                                        ?>
                                        <tr>
                                            <td style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{  $lgu->account_group_name  }}</td>
                                            <td style="text-align:left">
                                                @if ( $ledgertotal  > 1)
                                                {{ new_number_format($ledgertotal??0) }}(DR)
                                                @else
                                                {{ new_number_format($ledgertotal *-1 ??0) }}(CR)
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                
                            @endforeach
                            
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                            @if($liabilities)
                            <tr style="background-color: rgb(207, 107, 107);">
                                <td>Liabilities (Total)</td>
                                <td style="text-align:right">
                                    @if ($liability_grand_total> 1)
                                    {{ new_number_format($liability_grand_total) }} (Dr)
                                    @else
                                    {{ new_number_format(($liability_grand_total)*-1) }} (Cr)
                                    @endif

                                </td>
                            </tr>
                            @endif
                           

                        </tbody>
                    </table>
                </div>
                <?php
                    $difference = 0;
                    $rightSide = $liability_grand_total ;
                    $leftSide = $asset_grand_total + $getProfit['profit']+ $stockValue -$getProfit['loss'];
    
                    if($rightSide > 1 && $leftSide >1){
                        $difference= $leftSide - $rightSide;
                    }elseif($rightSide < 1 && $leftSide <1){
                        $difference=$rightSide + $leftSide;
                    }
                    elseif($rightSide> 1 && $leftSide < 1){ 
                        $difference=$rightSide + $leftSide; 
                    }elseif($rightSide < 1 && $leftSide> 1){
                        $difference= $leftSide + $rightSide;
                    }
                ?>


            </div>

                </div>
            </div>
        </div>

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
    
    function printData() {
        var divToPrint = document.getElementById('main_table');
        
        var body = $('body').html();
        $('body').html(divToPrint.outerHTML);
        window.print();
        $('body').html(body);
        
        // var htmlToPrint = '' +
        //     '<style type="text/css">' +
        //     'table th, table td {'
        //     +'border:1px solid #000;'
        //     +
        //     '}' +
        //     '</style>';
        //     htmlToPrint += divToPrint.outerHTML;
        //     newWin = window.open("");
        //     window.document.write(htmlToPrint);
        //     window.print();
        // window.close();
    }
    
</script>
@endpush

