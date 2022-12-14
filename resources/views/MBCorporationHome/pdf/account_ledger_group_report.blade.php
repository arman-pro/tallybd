@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Account Ledger Group Report")

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
    
    @page {
        page: a4;
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

    .clearfix {
        display: both;
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
        $dr = 0;
        $cr = 0;    
    ?>
   
    <div class="p-0 content_area" >
        <div>
            <header style="text-align: center;">
                <h2 class="m-0" style="font-weight: 800;margin:0;">{{ $company->company_name }}</h2>
                <p class="m-0" style="margin:0;">
                    {{ $company->company_address }}, Tel: {{ $company->phone }}, Call:
                    {{ $company->mobile_number }}
                </p>
                <h4 style="margin:0;">Account Group Ledger</h4>
                <p class="clearfix m-0">
                <span class="float-start"><b>Account Group Name :</b> {{ $account_group_list->account_group_name }}</span>
                <span class="float-end">
                    <b>From :</b> {!! $formDate . ' <b>to</b> ' . $toDate !!}
                </span>
                </p>
            </header>
            <main >
            <table class="pdf-table" style="margin-top:10px;">
                <thead>
                    <tr style="font-size:14px;font-weight: 800;">
                        <th >Sl No. </th>
                        <th >Party Name/ Ledger Name </th>
                        <th >Mobile Number </th>
                        <th >Debit(Dr)</th>
                        <th >Credit(Cr)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $number = 0;
                        
                    ?>
                    @foreach ($groupAccount_ledger as $key => $item)
                    <?php
                        $i = 0;
                        $x = 0;

                        $account_tran = App\AccountLedgerTransaction::where('ledger_id', $item->id)
                            // ->whereBetween('date', [$formDate, $toDate])
                            //->where('date', '>=', $formDate)
                            ->where('date', '<=', $toDate)
                            ->get()
                            ->unique('account_ledger__transaction_id');
                        $result = $account_tran->sum('debit') - $account_tran->sum('credit');
                        if ($result > 1) {
                            $dr += $result;
                        } else {
                            $cr += $result;
                        }
                    
                    ?>
                    @if($filter == 'filter' && $result != 0)
                
                    <tr class="text-right" style="font-size:14px;">
                        <td >{{$number += 1}}</td>
                        <td >{{ $item->account_name }}</td>
                        <td >{{ $item->account_ledger_phone ?? 'N/A' }}</td>
                        @if ($result > 0)
                            <td style="text-align:right;">{{ new_number_format($result) }} </td>
                        @else
                            <td style="text-align:right;">{{ '-' }}</td>
                        @endif
                        @if ($result < 0)
                            <td style="text-align:right;">
                                {{ new_number_format($result * -1) }} </td>
                        @else
                            <td style="text-align:right;">
                                {{ '-' }}</td>
                        @endif
                    </tr>
                  
                    @endif
                    
                    @if($filter == 'all')
                     <tr class="text-right" style="font-size:14px;">
                        <td >{{$number += 1}}</td>
                        <td >{{ $item->account_name }}</td>
                        <td >{{ $item->account_ledger_phone ?? 'N/A' }}</td>
                        @if ($result > 0)
                            <td style="text-align:right;">{{ new_number_format($result) }} </td>
                        @else
                            <td style="text-align:right;">{{ '-' }}</td>
                        @endif
                        @if ($result < 0)
                            <td style="text-align:right;">
                                {{ new_number_format($result * -1) }} </td>
                        @else
                            <td style="text-align:right;">
                                {{ '-' }}</td>
                        @endif
                    </tr>
                     
                    @endif
                @endforeach
                
                @if($account_group_list->groupUnders->isNotEmpty())
                    @foreach($account_group_list->groupUnders as $group_under)
                        <?php
                            $account_group_ids = $group_under->get_all_under_group_id($group_under);

                            $account_tran_ = App\AccountLedgerTransaction::whereIn('ledger_id', function($query)use($account_group_ids){
                                return $query->from('account_ledgers')->select("id")->whereIn('account_group_id', $account_group_ids);
                            })
                            ->where('date', '<=', $toDate)
                            ->get()
                            ->unique('account_ledger__transaction_id');
                            $result = $account_tran_->sum('debit') - $account_tran_->sum('credit');
                            if ($result > 1) {
                                $dr += $result;
                            } else {
                                $cr += $result;
                            }
                        ?>                                    
                        <tr class="text-right" style="font-size:14px;">
                            <td >{{$number += 1}}</td>
                            <td >{{ $group_under->account_group_name }}</td>
                            <td >{{ $group_under->account_ledger_phone ?? 'N/A' }}</td>
                            @if ($result > 0)
                                <td style="text-align:right;">{{ new_number_format($result) }} </td>
                            @else
                                <td style="text-align:right;">{{ '-' }}</td>
                            @endif
                            @if ($result < 0)
                                <td style="text-align:right;">
                                    {{ new_number_format($result * -1) }} </td>
                            @else
                                <td style="text-align:right;">
                                    {{ '-' }}</td>
                            @endif
                        </tr>
                    @endforeach                                
                @endif
                <tr>
                    <td colspan="3" class="text-right">Grand Total</td>
                    <td width="30%"
                        style="text-align:right;font-size: x-large;padding: 5px 5px;">
                        {{ new_number_format($dr) }} </td>
                    <td d width="30%" style="text-align:right;font-size: x-large;padding: 5px 5px;">
                        {{ new_number_format(-1 * $cr) }} </td>
                </tr>
                </tbody>
            </table>
            </main>
            <footer>
            Printed on	@php
                    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                    echo $dt->format('j-m-Y , g:i a');
                @endphp
            </footer>
        </div>
    </div>
</div>

@endsection