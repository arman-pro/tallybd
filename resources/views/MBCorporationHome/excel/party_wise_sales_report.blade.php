<?php
    $company = App\Companydetail::first();
?>
<table>
    <thead>
        <tr>
            <th colspan="7">{{$company->company_name}}</th>
        </tr>
        <tr>
            <th colspan="7">{{$company->company_address}}</th>
        </tr>
        <tr>
            <th colspan="7">{{$company->phone}}</th>
        </tr>
        <tr>
            <th colspan="7">Party Wise Sales</th>
        </tr>
        <tr>
            <th colspan="7">From : {{date('d-m-Y', strtotime($fromDate))}} TO : {{date('d-m-Y', strtotime($toDate))}}</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Vch.No</th>
            <th>Account Lager</th>
            <th>Item Details</th>
            <th>Total Qty</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <?php
        $purchases = App\SalesAddList::where('account_ledger_id',$account_ledger_id)
            ->wherebetween('date', [$fromDate, $toDate])
            ->get();
        $total_qty= 0;
    ?>
    <tbody>
        @foreach($purchases as $purchases_row)
        <tr>
            <td>{{ date('d-m-Y',strtotime($purchases_row->date)) }}</td>
            <td>{{$purchases_row->product_id_list}}</td>
            <td>{{ optional($purchases_row->ledger)->account_name??'-'}}</td>
            <td>
            <?php
                $total_price = 0;
                $subtotal_price= 0;
                $item_detais=App\DemoProductAddOnVoucher::where("product_id_list", $purchases_row->product_id_list)->with('item')->get();
            
                $ledger_output = [];
                $item_output = [];
                $qty_output = [];
                $total_price_output = [];
                
                foreach ($item_detais as $item_detais_rowss) {
                    $subtotal_price=$subtotal_price+$item_detais_rowss->subtotal_on_product;
                    $ledger_item = optional($item_detais_rowss->item)->name ?? ' ';
                    array_push($ledger_output, $ledger_item);
                    $total_qty += $item_detais_rowss->qty;
                    $item_ = number_format($item_detais_rowss->qty, 2) ?? "0";
                    array_push($item_output, $item_);
                    $qty_item = number_format($item_detais_rowss->price, 2) ?? "0";
                    array_push($qty_output, $qty_item);
                    $price_item = number_format(($item_detais_rowss->price * $item_detais_rowss->qty) , 2);
                    array_push($total_price_output, $price_item);
                }
                $total_price = $subtotal_price + $purchases_row->other_bill - $purchases_row->discount_total;
            ?>
                {!!implode("<br style='mso-data-placement:same-cell;' />", $ledger_output)!!}
            </td>
            <td>{!!implode("<br style='mso-data-placement:same-cell;' />", $item_output)!!}</td>
            <td>{!!implode("<br style='mso-data-placement:same-cell;' />", $qty_output)!!}</td>
            <td>{!!implode("<br style='mso-data-placement:same-cell;' />", $total_price_output)!!}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <th>{{number_format($total_qty, 2) }} </th>
            <td>&nbsp;</td>
            <th>{{number_format($purchases->sum('grand_total'), 2) }} Tk. </th>
        </tr>
    </tfoot>
</table>