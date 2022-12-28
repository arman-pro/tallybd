@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Bank Interest Report")

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12"  id="main_table">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Bank Interest Calculatio Report</h4>
                </div>
                <div class="card-body">
                    <table id="printArea" class="table table-bordered" cellspacing="0"
                    style="border: 1px solid rgb(53, 53, 53);text-align: center;width:100%">
    
                    <td colspan="7" style="text-align: center;">
                            <?php
                                $company = App\Companydetail::get();
                                $openDr = 0;
                                $openCr = 0;
                                $openBalance = 0;
                               
                            ?>
    
                            @foreach($company as $company_row)
    
                            <h3 style="margin:0;">{{$company_row->company_name}}</h3>
                            <p style="margin:0;">{{$company_row->company_address}}, <br>{{$company_row->phone}} Call:
                                {{$company_row->mobile_number}}</p>
                            @endforeach
                            <h4 style="margin:0;">Bank Interest Calculation </h4>    
                            <p style="text-align: left; margin:0;">Account : <b>{{ $ledger->account_name }} </p> </b>
                            <p style="text-align: left; margin:0;" class="clearfix">
                                <span class="float-start">
                                    Account Address: {{ $ledger->account_ledger_address.' '.$ledger->account_ledger_phone }}
                                </span> 
                                <span class="float-end">
                                    <b>From : {{date('d-m-Y', strtotime($formDate))}} To :  {{date('d-m-Y', strtotime($toDate))}}<b></b>
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
                    
                    <?php
                        $opening = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) as credit')
                            ->where('ledger_id',$ledger_id)
                            ->where('date', '<', $formDate)
                            ->first();
                           
                        $transactions = App\AccountLedgerTransaction::selectRaw('SUM(debit) as debit, SUM(credit) as credit, date')
                            ->where('ledger_id',$ledger_id)
                            ->where('date', '>=', $formDate)
                            ->where('date', '<=', $toDate)
                            ->groupBy('date')
                            ->get();
                            
                        $opening_ = $opening->debit - $opening->credit;
                        $start_date = $formDate;
                        
                        $total_interest = 0;
                        
                    ?>
                    @foreach($transactions as $key => $trans)
                    <?php
                        $opening_ = ($trans->debit - $trans->credit) + ($opening_ ?? 0);
                        if($key == 0) {
                            $earlier = new DateTime($start_date);
                        }else {
                            $earlier = new DateTime($trans->date);
                        }
                        
                        $later = new DateTime($transactions[$key+1]->date ?? $toDate);
                        $abs_diff = $later->diff($earlier)->format("%a");
                        $start_date = $trans->date; // again set start date
                        
                        $interest_percent = ((($opening_ * $percent) / 100) / $bankDays) * $abs_diff;
                        $total_interest += $interest_percent;
                       
                    ?>
                    <tr style="font-size:14px;">
                        <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 20%;">{{new_number_format($opening_, 2)}}</td>
                        <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 10%;">{{date("d-m-y", strtotime($trans->date))}}</td>
                        <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 5%;">{{$abs_diff}}</td>
                        <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 20%;text-align: center;">{{number_format($interest_percent, 2)}}</td>
                        <td style="border: 1px solid rgb(53, 53, 53);padding: 5px 5px;width: 20%;text-align: center;">{{number_format($total_interest, 2)}}</td>
                    </tr>
                    @endforeach
    
                   
                </table>
                </div>
                <div class="card-footer text-center">
                    <button type="button" class="btn btn-success btn-lg text-light fw-bold" onclick="printData()"><i class="fa fa-print"></i> Print</button>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@push('js')
<script>
    function printData(){
        var divToPrint = document.getElementById('printArea');
        var body = $('body').html();
        $('body').html(divToPrint.outerHTML);
        window.print();
        $('body').html(body);
    }
    </script>
@endpush