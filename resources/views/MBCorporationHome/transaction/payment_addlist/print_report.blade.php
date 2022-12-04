@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "payment_voucher_".date('d_m_y'))

@push('css')
<style>
    .head_table {
        width: 100%;
        border: none;
        border-collapse: collapse;
    }

    .head_table tr, .head_table th, .head_table td {
        border: none;
    }

    .signature-by {
        position: absolute;
        left: 20px;
        bottom: 20px;
    }

    .verifyed-by {
        position: absolute;
        bottom: 20px;
        right: 50%;
    }

    .border {
        border: 1px solid black;
    }
</style>
@endpush

@section('container')
<div class="invoice-title">
    <div>
        &nbsp;
    </div>
    <div class="font-bold underline uppercase">
        Payment Voucher
    </div>
    <div>
        &nbsp;
    </div>
</div>
<div class="account-title">
    <table class="head_table">
        <tbody>
            <tr>
                <th class="text-left" style="width: 100px;">Voucher No:</th>
                <td style="width: 65%;">{{$payment->vo_no ?? "N/A"}}</td>
                <th class="text-right">Date:</th>
                <td>{{date("d/m/y", strtotime($payment->date))}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 100px;">Account Mode:</th>
                <td style="width: 65%;">{{$payment->accountMode->account_name ?? "N/A"}}</td>
                <th class="text-right">&nbsp;</th>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="invoice-body">
    <?php
        $total = 0;
        $total_qty = 0;
        $grand_total = $payment->amount;
    ?>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width:85%">Particulars</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left padding-left-5">{{$payment->accountMode->account_name ?? "N/A"}}</td>
                <td>{{new_number_format($payment->amount)}}</td>
            </tr>
            <tr class="border-none">
                <td class="border-none">&nbsp;</td>
                <td class="border" rowspan="8"></td>
            </tr>
        @for($i = 0; $i < 7; $i++)
            <tr class="border-none">
                <td class="border-none">&nbsp;</td>
            </tr>
        @endfor 
            <tr>
                <td class="text-left padding-left-5">Note: {{$payment->description ?? "N/A"}}</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
       
        <tfoot>
            <tr>
                <td class="text-left padding-left-5">Grand Total</td>
                <td>{{new_number_format($payment->amount ?? 0)}}</td>
            </tr>            
        </tfoot>
    </table>
    <div style="margin-top: 0.1in">
        <b>Amount In Word:</b> {{number_to_word($grand_total)}} Only
    </div>
</div>
<div class="signature-by">
    Signature
</div>
<div class="verifyed-by">
    Verified By
</div>
<div class="signature-box">
    Authorised Signatory
</div>
@endsection