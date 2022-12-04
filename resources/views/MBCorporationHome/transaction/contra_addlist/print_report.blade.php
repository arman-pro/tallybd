@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "journal_report_".date('d_m_y'))

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


</style>
@endpush

@section('container')
<div class="invoice-title">
    <div>
        &nbsp;
    </div>
    <div class="font-bold underline uppercase">
        Contra Voucher
    </div>
    <div>
        &nbsp;
    </div>
</div>
<div class="account-title">
    <table class="head_table">
        <tbody>
            <tr>
                <th class="text-left" style="width: 120px;">Voucher No.:</th>
                <td style="width: 65%;">{{$payment->vo_no ?? "N/A"}}</td>
                <th class="text-right">Date:</th>
                <td>{{date("d/m/y", strtotime($payment->date))}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="invoice-body">
    <?php
        $total_dr = 0;
    ?>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width:40%">Particulars</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Narration</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $contra_drcr = App\DemoContraJournalAddlist::where('vo_no', $payment->vo_no)->with(['ledger'])->get();
        ?>
        @if($contra_drcr->isNotEmpty())
            @foreach($contra_drcr as $contra)
            <?php
               if($contra->drcr == 'Dr') {
                    $total_dr += $contra->amount;
                }
            ?>
                <tr>
                    <td class="padding-left-3 text-left">{{$contra->ledger->account_name}}</td>
                    @if($contra->drcr == 'Dr')
                    <td>{{new_number_format($contra->amount)}}</td>
                    <td>&nbsp;</td>
                    @elseif($contra->drcr  == 'Cr')
                    <td>&nbsp;</td>
                    <td>{{new_number_format($contra->amount)}}</td>
                    @endif
                    <td>{{$contra->note}}</td>
                </tr>
            @endforeach
        @endif
        <?php
            $empty_row = 8 - ($contra_drcr->count() ?? 0);
        ?>
        @for($i = 0; $i < $empty_row; $i++)
            <tr class="border-none">
                <td class="border-none" colspan="4">&nbsp;</td>
            </tr>
        @endfor
        </tbody>
        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>{{new_number_format($total_dr)}}</td>
                <td>{{new_number_format($total_dr)}}</td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
    <div style="margin-top: 0.1in">
        <b>Amount In Word:</b> {{number_to_word($total_dr)}} Only
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