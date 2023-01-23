@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div >
                        @if(request()->to_date)
                        <a href="{{route('balance_sheet_report')}}" class="btn btn-sm btn-danger">Clear</a>
                        @endif
                        <button type="button" class="btn btn-danger text-light fw-bold" id="filter_btn">Filter</button>
                        <button class="btn btn-success text-light fw-bold" type="button" id="print_btn" onclick="printData()"><i class="fa fa-print"></i> Print</button>
                        <a class="btn btn-primary text-light fw-bold" href="{{url()->full()}}?pdf=1"><i class="fas fa-file-pdf"></i> Pdf</a>
                    </div>
                    <div class="mt-3">
                        <form method="GET" action="{{route('balance_sheet_report')}}" id="filter_form">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 px-3">
                                    <div class="form-group">
                                        <label for="from_date">From Date</label>
                                        <input type="date" class="form-control" value="2021-01-01" name="from_date" id="from_date" />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">      
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Balance Sheet</h4>
                </div>
                <div class="card-body">
                    <div id="main_table">
                        <?php
                            $from = null;
                            $to = null;
                            if(request()->form_date && request()->to_date){
                                $from = date('Y-m-d', strtotime(request()->form_date));
                                $to = date('Y-m-d', strtotime(request()->to_date));
                            }
                            $company = App\Companydetail::first();
                        ?>
                        <h3 class="text-center">{{$company->company_name}}</h3>
                        <p class="m-0 p-0 text-center">
                            <b>{{$company->company_address}}, Tel: {{$company->phone}}, Call: {{$company->mobile_number}}</b>
                        </p>
                        <p class="m-0 p-0 text-center"><b>Balance Sheet</b></p>
                        @if($from && $to)
                            <p class="m-0 p-0 text-center">From : {{$from}} T0 : {{ $to }}</p>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead class="text-center bg-success text-light">
                                        <tr>
                                            <th>Asset </th>
                                            <th>Total</th>
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
                                                    $ledger_summery = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                    ->where('ledger_id', $account_ledger_under_group->id )
                                                    ->groupBy('ledger_id')
                                                    ->first();
                                                    $total += $ledger_summery->grand_total;
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
                                            $AccountLedger = App\AccountLedger::where('account_group_id', $lg->id )->get();
                                            $ledgertotal = 0;
                                            foreach($AccountLedger as $aL){
                                                $LedgerSummary = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                ->where('ledger_id', $aL->id )
                                                ->groupBy('ledger_id')
                                                ->first();
                                                $ledgertotal += $LedgerSummary->grand_total;
                                            }
                                            $asset_grand_total += $ledgertotal;
                                            // account_group_under_id
                                            
                                            $Liabilities_group_under = App\AccountGroup::where('account_group_under_id', $lg->id)->get();
                                            
                                            foreach($Liabilities_group_under as $lgu){
                                               
                                                $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                                $ledgertotal_u = 0;
                                                foreach($AccountLedger_u as $aLu){
                                                    $LedgerSummaryu = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                    ->where('ledger_id', $aLu->id )
                                                    ->groupBy('ledger_id')
                                                    ->first();
                                                    $ledgertotal_u += $LedgerSummaryu->grand_total;
                                                }
                                                 $ledgertotal_u += recursion_group($lgu->id, 0);
                                                $asset_grand_total += $ledgertotal_u;
                                                $ledgertotal += $ledgertotal_u;
                                            }
                                        ?>
                                            <tr>
                                                <td class="text-left"><b>{{$lg->account_group_name  }}</b></td>
                                                <td class="text-end">
                                                    <b>
                                                        @if ( $ledgertotal  > 1)
                                                            {{ new_number_format($ledgertotal??0, 2) }}(DR)
                                                        @else
                                                            {{ new_number_format($ledgertotal *-1 ??0, 2) }}(CR)
                                                        @endif
                                                    </b>
                                                </td>
                                            </tr>
                                                @foreach($Liabilities_group_under as $lgu)
                                                    <?php
                                                        $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                                        $ledgertotal = 0;
                                                        foreach($AccountLedger_u as $aLu){
                                                            $LedgerSummaryu = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                            ->where('ledger_id', $aLu->id )
                                                            ->groupBy('ledger_id')
                                                            ->first();
                                                            $ledgertotal += $LedgerSummaryu->grand_total;
                                                        }
                                                        
                                                        $ledgertotal += recursion_group($lgu->id, 0);
                                                       
                                                    ?>
                                                    <tr>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{  $lgu->account_group_name  }}</td>
                                                        <td>
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
                                            <td><strong>Stock Value</strong> </td>
                                            <td class="text-end"><b>{{ new_number_format($stockValue??0, 2) }}</b></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Profit/Loss</strong> </td>
                                            @if($getProfit['profit']  > 0)
                                            <td class="text-end"><b>{{ new_number_format($getProfit['profit']??0, 2) }} (DR)</b></td>
                                            @else
                                            <td class="text-end"><b>- {{ new_number_format($getProfit['loss']??0, 2) }} (CR)</b></td>            
                                            @endif            
                                        </tr>           
            
                                        @if($assets)
                                        <tr class="bg-success text-light">
                                            <td> Assets (Total)</td>
                                            <td class="text-end">
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
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <thead class="text-center bg-success text-light">
                                        <tr>
                                            <th>Liabilities</th>
                                            <th>Total</th>
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
                                                $LedgerSummary = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                ->where('ledger_id', $aL->id)
                                                ->groupBy('ledger_id')
                                                ->first();
                                                $ledgertotal +=$LedgerSummary->grand_total;
                                            }
                                            $liability_grand_total +=$ledgertotal;
                                            // account_group_under_id
                                            
                                            $Liabilities_group_under = App\AccountGroup::where('account_group_under_id', $lg->id)->get();
                                            
                                            foreach($Liabilities_group_under as $lgu){
                                               
                                                $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                                $ledgertotal_u=0;
                                                foreach($AccountLedger_u as $aLu){
                                                    $LedgerSummaryu = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                    ->where('ledger_id', $aLu->id )
                                                    ->groupBy('ledger_id')
                                                    ->first();
                                                    $ledgertotal_u += $LedgerSummaryu->grand_total;
                                                }
                                                $ledgertotal_u += recursion_group($lgu->id, 0);
                                                $liability_grand_total +=$ledgertotal_u;
                                                $ledgertotal +=$ledgertotal_u;
                                            }
                                            
                                        ?>   
                                            <tr>
                                                <td><b>{{  $lg->account_group_name  }}</b></td>
                                                <td class="text-end">
                                                    <b>
                                                        @if ( $ledgertotal  > 1)
                                                        {{ new_number_format($ledgertotal??0, 2) }}(DR)
                                                        @else
                                                        {{ new_number_format($ledgertotal *-1 ??0, 2) }}(CR)
                                                        @endif
                                                    </b>
                                                </td>
                                            </tr>                                            
                                            @foreach($Liabilities_group_under as $lgu)
                                                <?php
                                                    $AccountLedger_u = App\AccountLedger::where('account_group_id', $lgu->id )->get();
                                                    $ledgertotal=0;
                                                    foreach($AccountLedger_u as $aLu){
                                                        $LedgerSummaryu = App\LedgerSummary::selectRaw('SUM(grand_total) as grand_total')
                                                        ->where('ledger_id', $aLu->id)
                                                        ->groupBy('ledger_id')
                                                        ->first();
                                                        $ledgertotal += $LedgerSummaryu->grand_total;
                                                    }
                                                    $ledgertotal += recursion_group($lgu->id, 0);
                                                ?>

                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{  $lgu->account_group_name  }}</td>
                                                    <td>
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
                                        <tr class="bg-danger text-light">
                                            <td>Liabilities (Total)</td>
                                            <td class="text-end">
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
                            elseif($rightSide> 1 && $leftSide < 1)
                            { $difference=$rightSide + $leftSide; }elseif($rightSide < 1 && $leftSide> 1){
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
            /* size: A4;*/
            /* DIN A4 standard, Europe */
            /*margin: 0%;
            padding: 0%;
            width: 100% */
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

@push('js')
<script lang='javascript'>
    function printData()
    {
        // var divToPrint = document.getElementById('main_table');
        // document.body.innerHTML = divToPrint;
        // window.print();
    }
</script>
<script>
    $(document).ready(function(){
        $('#filter_form').hide();
        $('#filter_btn').on('click', function(){
            $('#filter_form').toggle('slow');
        });

        $('#print_btn').click(function(){
            var print_part = $('#main_table').html();
            var body_part = $('body').html();
            $('body').html(print_part);
            window.print();
            $('body').html(body_part);
        });
    });
</script>
@endpush

