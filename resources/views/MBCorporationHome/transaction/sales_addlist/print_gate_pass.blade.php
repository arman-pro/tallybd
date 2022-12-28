@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "gate_pass_".date('d_m_y'))

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
        Gate Pass
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
                <td style="width: 60%;">{{$sale_add->ledger->account_name ?? "N/A"}}</td>
                <th class="text-right">Date:</th>
                <td>{{date("d/m/y", strtotime($sale_add->date))}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 80px;">Address:</th>
                <td style="width: 60%;">{{$sale_add->ledger->account_ledger_address ?? "N/A"}}</td>
                <th class="text-right">Voucher No:</th>
                <td>{{$sale_add->product_id_list}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 80px;">&nbsp;</th>
                <td style="width: 60%;">&nbsp;</td>
                <th class="text-right">Delivery To:</th>
                <td>{{$sale_add->delivered_to_details ?? "N/A"}}</td>
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
                <th style="width:80px;">SL</th>
                <th style="width:70%">Description</th>                
                <th>Unit</th>
                <th>Qty.</th>
            </tr>
        </thead>
        <tbody>
        @if($sale_add->demoProducts->isNotEmpty())
            @foreach($sale_add->demoProducts as $demo_product)
            <?php
                $total_qty += $demo_product->qty ?? 0;
            ?>
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td class="padding-left-3 text-left">{{$demo_product->item->name}}</td>
                    <td>{{$demo_product->item->unit->name}}</td>
                    <td>{{$demo_product->qty}}</td>
                </tr>
            @endforeach
        @endif
        
        @for($i = 0; $i < (9 - $sale_add->demoProducts->count() ?? 0); $i++)
            <tr class="border-none">
                <td class="border-none" colspan="4">&nbsp;</td>
            </tr>
        @endfor
        </tbody>
        <tfoot>
            <tr>
                <td class="text-left padding-left-5" colspan="2">Total</td>
                <td></td>
                <td>{{new_number_format($total_qty ?? 0)}}</td>
            </tr>
        </tfoot>
    </table>
    <div style="margin-top: 0.1in">
        <b>Amount In Word:</b> {{number_to_word($total_qty)}} Only
    </div>
</div>
<div class="signature-by">
    Receiver
</div>
<div class="verifyed-by">
    Accontant
</div>
<div class="signature-box">
    Authorised Signatory
</div>
@endsection