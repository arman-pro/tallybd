@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "Sale Order_".date('d_m_y'))

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
        Sale Order
    </div>
    <div>
        &nbsp;
    </div>
</div>
<div class="account-title">
    <table class="head_table">
        <tbody>
            <tr>
                 @php
    $purchases = App\SalesOrderAddList::where('product_id_list',$product_id_list)->first();
    $account = App\AccountLedger::where('id',$purchases->account_ledger_id)->first();
    @endphp
                <th class="text-left" style="width: 80px;">Account:</th>
                <td style="width: 60%;">{{$account->account_name}} </td>
                <th class="text-right">Date:</th>
                <td>{{date("d-m-Y", strtotime($purchases->date))}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 80px;">Address:</th>
                <td style="width: 60%;"> {{$purchases->ledger->account_ledger_address ?? "N/A"}}</td>
                <th class="text-right">Voucher No:</th>
                <td>{{$product_id_list}}</td>
            </tr>
            <tr>
                <th class="text-left" style="width: 80px;">&nbsp;</th>
                <td style="width: 60%;">&nbsp;</td>
                <th class="text-right">Delivery To:</th>
                <td>{{$purchases->delivered_to_details ?? "N/A"}}</td>
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
                <th>Unit</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        @php
            $i = 0;
            $total_qty = 0;
            $total_amount = 0;
            $DemoProductAddOnVoucher = App\DemoProductAddOnVoucher::where('product_id_list',$product_id_list)->get();
            // dd($DemoProductAddOnVoucher);
            @endphp
            @foreach($DemoProductAddOnVoucher as $row)
            @php
            $i++;
            $total_qty = $total_qty + $row->qty ;
            $total_amount = $total_amount + ($row->price *
            $row->qty) ;

            $item = App\Item::where('id',$row->item_id)->first();
            @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td class="padding-left-3 text-left">{{$item->name." ".$item->unit->name}}</td>
                    <td>{{ number_format($row->qty, 2)}}</td>
                    <td>{{$item->unit->name}}</td>
                    <td>{{number_format ($row->price,2)}}</td>
                    <td>{{$item->unit->name}}</td>
                    <td>{{ number_format($row->price * $row->qty, 2)}}</td>
                </tr>
            @endforeach
    
        
       
            <tr class="border-none">
                <td class="border-none" colspan="7">&nbsp;</td>
            </tr>
    
        </tbody>
        <?php
            $grand_total = ($sales_order_addlist->other_bill ?? 0) + $total;
        ?>
        <tfoot>
            <tr>
                <td class="text-left padding-left-5" colspan="2">Total</td>
                <td>{{$total_qty}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{new_number_format($total_amount, 2)}}</td>
            </tr>
            <tr>
               
            </tr>
            <tr>
                <td class="text-left padding-left-5" colspan="6">Grand Total</td>
                <td>{{new_number_format($total_amount, 2)}}</td>
            </tr>
        </tfoot>
    </table>
    <div style="margin-top: 0.1in">
        <b>Amount In Word:</b> {{number_to_word($total_amount, 2)}} Only
    </div>
</div>
                         @if($purchases->md_signature==1)
                    		<img src="{{asset('MBCorSourceFile')}}/assets/images/Approve2.png"width="60" height="60">'
                    		@endif
                            <p> Signatory</p>
<div class="signature-box">
    Authorised Signatory
</div>
@endsection