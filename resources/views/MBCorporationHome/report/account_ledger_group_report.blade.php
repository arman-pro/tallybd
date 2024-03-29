@extends('MBCorporationHome.apps_layout.layout')
@section("Account Ledger Group Report")

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Account Ledger Group Report</h4>
                </div>
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-12" id="main_table">
                            <table  class="table table-bordered" cellspacing="0" style="text-align: center;border:1px solid black !important">
                                <thead>
                                    <tr>
                                        <th colspan="8" style="text-align: center;">
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
                                        <th width="10%" style="border:1px solid  black;padding: 5px 5px; font-weight:bold;">Sl No. </th>
                                        <th width="50%" style="border:1px solid  black;padding: 5px 5px; font-weight:bold;">Party Name/ Ledger Name </th>
                                        <th width="10%" style="border:1px solid  black;padding: 5px 5px; font-weight:bold;">Mobile Number </th>
                                        <th width="25%" style="border:1px solid  black;text-align: right;padding: 5px 5px; font-weight:bold;">Debit(Dr)</th>
                                        <th width="25%" style="border:1px solid  black;text-align: right;padding: 5px 5px; font-weight:bold;">Credit(Cr)</th>
                                    </tr>
                                </thead>
            
            
                                <tbody>
                                    <?php
                                        $number = 0;
                                    ?>
                                    @if($account_group_list->groupUnders->isNotEmpty())
                                        @foreach($account_group_list->groupUnders as $group_under)
                                            <?php
                                                $account_group_ids = $group_under->get_all_under_group_id($group_under);
                                                $account_tran_ = App\AccountLedgerTransaction::selectRaw("SUM(debit) as debit, SUM(credit) as credit")
                                                ->whereIn('ledger_id', function($query)use($account_group_ids){
                                                    return $query->from('account_ledgers')->select("id")->whereIn('account_group_id', $account_group_ids);
                                                })
                                                ->groupBy('ledger_id')
                                                ->where('date', '<=', $toDate)
                                                ->get();
                                                $result = $account_tran_->sum('debit') - $account_tran_->sum('credit');
                                                if ($result > 1) {
                                                    $dr += $result;
                                                } else {
                                                    $cr += $result;
                                                }
                                            ?>                                    
                                            <tr class="text-right" style="font-size:14px;Color:Black">
                                                <td width="10%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{$number += 1}}</td>
                                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;font-weight:bold;">{{ $group_under->account_group_name }}</td>
                                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;font-weight:bold;">{{ $group_under->account_ledger_phone ?? 'N/A' }}</td>
                                                @if ($result > 0)
                                                    <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ new_number_format($result) }} </td>
                                                @else
                                                    <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ '-' }}</td>
                                                @endif
                                                @if ($result < 0)
                                                    <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                                        {{ new_number_format($result * -1) }} </td>
                                                @else
                                                    <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                                        {{ '-' }}</td>
                                                @endif
                                            </tr>
                                        @endforeach                                
                                    @endif
                                   
                                    @foreach ($groupAccount_ledger as $key => $item)
                                    <?php
                                        $i = 0;
                                        $x = 0;
            
                                        $account_tran = App\AccountLedgerTransaction::selectRaw("SUM(credit) as credit, SUM(debit) as debit")
                                            ->where('ledger_id', $item->id)
                                            // ->whereBetween('date', [$formDate, $toDate])
                                            //->where('date', '>=', $formDate)
                                            ->where('date', '<=', $toDate)
                                            ->groupBy('ledger_id')
                                            ->get();
                                        $result = $account_tran->sum('debit') - $account_tran->sum('credit');
                                       
                                        if ($result > 1) {
                                            $dr += $result;
                                        } else {
                                            $cr += $result;
                                        }
                                    
                                    ?>
                                    @if($filter == 'filter' && $result != 0)
                                    <tr class="text-right" style="font-size:14px;Color:Black">
                                        <td width="10%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{$number += 1}}</td>
                                        <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_name }}</td>
                                        <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_ledger_phone ?? 'N/A' }}</td>
                                        @if ($result > 0)
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ new_number_format($result) }} </td>
                                        @else
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ '-' }}</td>
                                        @endif
                                        @if ($result < 0)
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                                {{ new_number_format($result * -1) }} </td>
                                        @else
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                                {{ '-' }}</td>
                                        @endif
                                    </tr>
                                  
                                    @endif
                                    
                                    @if($filter == 'all')
                                     <tr class="text-right" style="font-size:14px;Color:Black">
                                        <td width="5%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{$number += 1}}</td>
                                        <td width="35%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;text-align: left;">{{ $item->account_name }}</td>
                                        <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_ledger_phone ?? 'N/A' }}</td>
                                        @if ($result > 0)
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ new_number_format($result) }} </td>
                                        @else
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ '-' }}</td>
                                        @endif
                                        @if ($result < 0)
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                                {{ new_number_format($result * -1) }} </td>
                                        @else
                                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                                {{ '-' }}</td>
                                        @endif
                                    </tr>
                                     
                                    @endif
                                @endforeach
                                
                                
                                <tr>
                                    <td colspan="3" class="text-right" style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">Grand Total</td>
                                    <td width="30%"
                                        style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">
                                        {{ new_number_format($dr) }} </td>
                                    <td d width="30%"
                                        style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">
                                        {{ new_number_format(-1 * $cr) }} </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" class="btn btn-success btn-lg text-light fw-bold"  onclick="printData()"><i class="fa fa-print"></i> Print</a>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg text-light fw-bold" ><i class="fas fa-file-pdf"></i> Pdf</a>
                    <a href="{{url()->full()}}&excel=1" class="btn btn-primary btn-lg text-light fw-bold" ><i class="fas fa-file-excel"></i> Excel</a>
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
