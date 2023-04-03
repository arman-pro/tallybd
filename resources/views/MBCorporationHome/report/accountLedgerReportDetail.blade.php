@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Account Ledger Group Report Detail")

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Account Ledger Group Report Detail</h4>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-12" id="main_table">
                            <table  class="table table-bordered" cellspacing="0" style="text-align: center;border:1px solid black !important">
                                <thead>
                                    <tr>
                                        <th colspan="7" style="text-align: center;">
                                            @php
                                                $company = App\Companydetail::get();
                                                $dr = 0;
                                                $cr = 0;
                                            @endphp
            
                                            @foreach ($company as $company_row)
                                                <h2 class="m-0" style="font-weight: 650; font-family:Calisto MT; font-size:30px;Color:Black">{{ $company_row->company_name }}</h2>
                                                <p class="m-0">{{ $company_row->company_address }}, Tel: {{ $company_row->phone }}, Call:
                                                    {{ $company_row->mobile_number }}</p>
                                            @endforeach
                                            <h4 class="m-0">Account Ledger</h4>
                                            <p class="clearfix m-0">
                                                <span class="float-start"style="Color:Black"><b>Account Group Name :</b> {{ $account_group_list->account_group_name }}</span>
                                                <span class="float-end">
                                                    <b>From :</b> {!! $formDate . ' <b>to</b> ' . $toDate !!}
                                                </span>
                                            </p>            
                                        </th>
                                    </tr>
                                    <tr style="font-size:14px;font-weight: 700;color:black">
                                        <td width="10%" style="border:1px solid  black;padding:2px; font-weight:bold;" rowspan="2">Sl No. </td>
                                        <td width="30%" style="border:1px solid  black;padding: 2px; font-weight:bold;" rowspan="2">Party Name/ Ledger Name </td>
                                        <td width="10%" style="border:1px solid  black;padding: 2px; font-weight:bold;" rowspan="2">Pre. Balance</td>
                                        <td width="25%" style="border:1px solid  black;text-align: center;padding: 2px; font-weight:bold;" colspan="2">Transaction</td>
                                        <td width="25%" style="border:1px solid  black;text-align: center;padding: 2px; font-weight:bold;" colspan="2">Closing</td>
                                    </tr>
                                
                                    <tr style="font-size:14px;font-weight: 700;color:black">
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">Debit(Dr)</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">Credit(Cr)</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">Debit(Dr)</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">Credit(Cr)</td>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $i = 0;
                                        $totalOpeningBalance = 0;
                                        $totalDr = 0;
                                        $totalCr = 0;
                                        $totalClosingDr = 0;
                                        $totalClosingCr = 0;
                                    ?>
                                     @if($account_group_list->groupUnders->isNotEmpty())
                                        @foreach ($account_group_list->groupUnders as $key => $group_under)
                                        <?php
                                            $i++;
                                            $account_group_ids = $group_under->get_all_under_group_id($group_under);
                                            
                                            $opening_account_tran_ = App\AccountLedgerTransaction::selectRaw("SUM(debit) as debit, SUM(credit) as credit")
                                            ->whereIn('ledger_id', function($query)use($account_group_ids){
                                                return $query->from('account_ledgers')->select("id")->whereIn('account_group_id', $account_group_ids);
                                            })
                                            ->groupBy('ledger_id')
                                            ->where('date', '<=', $formDate)
                                            ->get();
                                            $transaction = App\AccountLedgerTransaction::selectRaw("SUM(debit) as debit, SUM(credit) as credit")
                                            ->whereIn('ledger_id', function($query)use($account_group_ids){
                                                return $query->from('account_ledgers')->select("id")->whereIn('account_group_id', $account_group_ids);
                                            })
                                            ->groupBy('ledger_id')
                                            ->where('date', '>', $formDate)
                                            ->where('date', '<=', $toDate)
                                            ->get();
                                            $transactionAmount = $transaction->sum('debit') - $transaction->sum('credit');
                                            $dr = 0;
                                            $cr = 0;
                                            if ($transactionAmount > 0) {
                                                $dr = $transactionAmount;
                                            } else {
                                                $cr = $transactionAmount;
                                            }
                                            
                                            $totalDr += $dr;
                                            $totalCr += $cr;
                                            
                                            $opening_balance = $opening_account_tran_->sum('debit') - $opening_account_tran_->sum('credit');
                                            
                                            $totalOpeningBalance += ($opening_balance ?? 0);
                                            
                                            $closing_balance = $opening_balance + $transactionAmount;
                                            
                                            $dr_close = 0;
                                            $cr_close = 0;
                                            if($closing_balance > 0) {
                                                $dr_close = $closing_balance;
                                            }else {
                                                $cr_close = $closing_balance;
                                            }
                                            
                                            $totalClosingDr += $dr_close;
                                            $totalClosingCr += $cr_close;
                                        ?>    
                                        <tr style="font-size:14px;font-weight: 700;color:black">
                                            <td style="border:1px solid  black;text-align: center;padding: 2px; font-weight:bold;">{{$i}}</td>
                                            <td style="border:1px solid  black;padding: 2px; font-weight:bold;">{{$group_under->account_group_name}}</td>
                                            <td style="border:1px solid  black;text-align: center;padding: 2px; font-weight:bold;">{{new_number_format($opening_balance)}}</td>
                                            <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($dr)}}</td>
                                            <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($cr)}}</td>
                                            <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($dr_close)}}</td>
                                            <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($cr_close)}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    
                                    @foreach ($groupAccount_ledger as $key => $item)
                                    <?php
                                        $i++;
            
                                        $openingAccountTransaction = App\AccountLedgerTransaction::selectRaw("SUM(credit) as credit, SUM(debit) as debit")
                                            ->where('ledger_id', $item->id)
                                            ->where('date', '<=', $formDate)
                                            ->groupBy('ledger_id')
                                            ->get();
                                            
                                        $transaction = App\AccountLedgerTransaction::selectRaw("SUM(credit) as credit, SUM(debit) as debit")
                                            ->where('ledger_id', $item->id)
                                            ->where('date', '>', $formDate)
                                            ->where('date', '<=', $toDate)
                                            ->groupBy('ledger_id')
                                            ->get();
                                        
                                        $transactionAmount = $transaction->sum('debit') - $transaction->sum('credit');
                                        $dr = 0;
                                        $cr = 0;
                                        if ($transactionAmount > 0) {
                                            $dr = $transactionAmount;
                                        } else {
                                            $cr = $transactionAmount;
                                        }
                                        
                                        $totalDr += $dr;
                                        $totalCr += $cr;
                                        
                                        $opening_balance = $openingAccountTransaction->sum('debit') - $openingAccountTransaction->sum('credit');
                                        $totalOpeningBalance += ($opening_balance ?? 0);
                                        
                                        $closeing_balance = $opening_balance + $transactionAmount;
                                        $dr_close = 0;
                                        $cr_close = 0;
                                        if($closeing_balance > 0) {
                                            $dr_close = $closeing_balance;
                                        }else {
                                            $cr_close = $closeing_balance;
                                        }
                                        
                                        $totalClosingDr += $dr_close;
                                        $totalClosingCr += $cr_close;
                                    
                                    ?>
                                    <tr style="font-size:14px;font-weight: 700;color:black">
                                        <td style="border:1px solid  black;text-align: center;padding: 2px; font-weight:bold;">{{$i}}</td>
                                        <td style="border:1px solid  black;padding: 2px; font-weight:bold;">{{$item->account_name}}</td>
                                        <td style="border:1px solid  black;text-align: center;padding: 2px; font-weight:bold;">{{new_number_format($opening_balance)}}</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($dr)}}</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($cr)}}</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($dr_close)}}</td>
                                        <td style="border:1px solid  black;text-align: right;padding: 2px; font-weight:bold;">{{new_number_format($cr_close)}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="font-size:14px;font-weight: 700;color:black">
                                        <td colspan="2" style="border:1px solid  black;padding: 2px; font-weight:bold;text-align:right;">Grand Total</td>
                                        <td style="border:1px solid  black;padding: 2px; font-weight:bold;text-align:center;">{{new_number_format($totalOpeningBalance)}}</td>
                                        <td style="border:1px solid  black;padding: 2px; font-weight:bold;text-align:right;">{{new_number_format($totalDr)}}</td>
                                        <td style="border:1px solid  black;padding: 2px; font-weight:bold;text-align:right;">{{new_number_format($totalCr)}}</td>
                                        <td style="border:1px solid  black;padding: 2px; font-weight:bold;text-align:right;">{{new_number_format($totalClosingDr)}}</td>
                                        <td style="border:1px solid  black;padding: 2px; font-weight:bold;text-align:right;">{{new_number_format($totalClosingCr)}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" class="btn btn-success btn-lg text-light fw-bold"  onclick="printData()"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
        </div>
    </div>  
</div>

<script lang='javascript'>
    function printData(){
        var divToPrint = document.getElementById('main_table');
        var body = $('body').html();
        // window.document.write(divToPrint.outerHTML);
        $('body').html(divToPrint.outerHTML);
        window.print();
        $('body').html(body);
        // window.close();
    }
</script>


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
    @media print {
        .main_table table {
            border: solid #000 !important;
            border-width: 1px 0 0 1px !important;
        }
       .main_table table th, td {
            border: solid #000 !important;
            border-width: 0 1px 1px 0 !important;
        }
    }

</style>
@endsection
