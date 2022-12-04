@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "Sale Return Report_".date('d_m_y'))

@section('container')
<div class="invoice-title">
    <div>
        <b>Vch.No:</b> {{$sale_return_add_list->product_id_list}}
    </div>
    <div class="font-bold underline uppercase">
        Invoice
    </div>
    <div>
        <b>Date:</b> {{date('d/m/y', strtotime($sale_return_add_list->date))}}
    </div>
</div>
<div class="account-title">
    <b>Account Name: </b>{{$sale_return_add_list->ledger->account_name}}
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
                <th>Unit</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        @if($sale_return_add_list->demoProducts->isNotEmpty())
            @foreach($sale_return_add_list->demoProducts as $demo_product)
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
        
        @for($i = 0; $i < (10 - $sale_return_add_list->demoProducts->count() ?? 0); $i++)
            <tr class="border-none">
                <td class="border-none" colspan="7">&nbsp;</td>
            </tr>
        @endfor
        </tbody>
        <?php
            $grand_total = ($sale_return_add_list->other_bill ?? 0) + $total;
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
            <tr>
                <td class="text-left padding-left-5" colspan="6">Discount</td>
                <td>{{new_number_format($sale_return_add_list->other_bill ?? 0)}}</td>
            </tr>
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