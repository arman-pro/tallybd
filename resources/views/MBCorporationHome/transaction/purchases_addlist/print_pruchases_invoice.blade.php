@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "Purchase Report_".date('d_m_y'))

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
</style>
@endpush

@section('container')
<div class="invoice-title">
    <div>
        &nbsp;
    </div>
    <div class="font-bold underline uppercase">
        Purchase Invoice
    </div>
    <div>
        &nbsp;
    </div>
</div>
<div class="account-title">
    <table class="head_table">
        <tbody>
            <tr>
                <th class="text-left" style="width: 80px;">Account:</th>
                <td style="width: 65%;">{{$purchase->ledger->account_name ?? "N/A"}}</td>
                <th class="text-right">Date:</th>
                <td>{{date("d/m/y", strtotime($purchase->date))}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 80px;">Address:</th>
                <td style="width: 65%;">{{$purchase->ledger->account_ledger_address ?? "N/A"}}</td>
                <th class="text-right">Voucher No:</th>
                <td>{{$purchase->product_id_list}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 80px;">&nbsp;</th>
                <td style="width: 65%;">&nbsp;</td>
                <th class="text-right">Delivery To:</th>
                <td>{{$purchase->delivered_to_details ?? "N/A"}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="invoice-body">
    <?php
        $total = 0;
        $total_qty = 0;
    ?>
    <table class="print-table">
        <thead>
            <tr>
                <th>SL</th>
                <th style="width:40%">Description</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Rate</th>
                <th>Per</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        @if($purchase->demoProducts->isNotEmpty())
            @foreach($purchase->demoProducts as $demo_product)
            <?php
                $total += ($demo_product->price * $demo_product->qty) ?? 0;
                $total_qty += $demo_product->qty ?? 0;
            ?>
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td class="padding-left-3 text-left">{{$demo_product->item->name}}</td>
                    <td>{{$demo_product->qty}}</td>
                    <td>{{$demo_product->item->unit->name}}</td>
                    <td>{{$demo_product->price}}</td>
                    <td>{{$demo_product->item->unit->name}}</td>
                    <td>{{new_number_format(($demo_product->price * $demo_product->qty), 2)}}</td>
                </tr>
            @endforeach
        @endif
        <?php
            $empty_row = 8 - ($purchase->demoProducts->count() ?? 0);
            if(!$purchase->other_bill)
                $empty_row += 1;
        ?>
        @for($i = 0; $i < $empty_row; $i++)
            <tr class="border-none">
                <td class="border-none" colspan="7">&nbsp;</td>
            </tr>
        @endfor
        </tbody>
        <?php
            $grand_total = ($purchase->other_bill ?? 0) + $total;
        ?>
        <tfoot>
            <tr>
                <td class="text-left padding-left-5" colspan="2">Total</td>
                <td>{{$total_qty}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{new_number_format($total ?? 0)}}</td>
            </tr>
            @if($purchase->other_bill)
            <tr>
                <td class="text-left padding-left-5" colspan="6">{{$purchase->expense_ledger->account_name ?? "N/A"}}</td>
                <td>{{new_number_format($purchase->other_bill ?? 0)}}</td>
            </tr>
            @endif
            <tr>
                <td class="text-left padding-left-5" colspan="6">Grand Total</td>
                <td>{{new_number_format($grand_total)}}</td>
            </tr>
        </tfoot>
    </table>
    <div style="margin-top: 0.1in">
        <b>Amount In Word:</b> {{number_to_word($grand_total)}} Only
    </div>
</div>
<div class="signature-box">
    Authorised Signatory
</div>
@endsection