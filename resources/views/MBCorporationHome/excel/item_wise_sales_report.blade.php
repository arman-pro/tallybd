<?php
    $d = [];
    $dd= [];
    $company_row = App\Companydetail::first();
?>
 <table>
    <thead>
        <tr>
            <th colspan="7">
               {{$company_row->company_name}}
            </th>
        </tr>
        <tr>
            <th colspan="7">
               {{$company_row->company_address}}
            </th>
        </tr>
        <tr>
            <th colspan="7">Item Wise Sales</th>
        </tr>
        <tr>
            <th colspan="7">From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </th>
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
        $total_amount= 0;
        $total_qty= 0;
    ?>
    <tbody>
        @foreach($item->demoProductAddOnVoucher??[] as $demo_row)
        <?php
            $purchases = App\SalesAddList::where('product_id_list',$demo_row->product_id_list)->get();
            $grand_total = 0;
            $total_amount += $demo_row->subtotal_on_product;
        ?>
        @foreach($purchases as $purchases_row)

        <tr>
            <td>{{ date('d-m-Y', strtotime($purchases_row->date)) }}</td>
            <td>{{$demo_row->product_id_list}}</td>
            <td>{{$purchases_row->ledger->account_name}}</td>
            <td>
            <?php
                $qty=0;
                $total_price = 0;
                $subtotal_price= 0;
                $item_detais=App\DemoProductAddOnVoucher::where("product_id_list", $purchases_row->product_id_list)->with('item')->get();

                $ledger_output = [];
                foreach ($item_detais as $item_detais_rowss) {
                    $qty = $qty+$item_detais_rowss->qty;
                    $subtotal_price=$subtotal_price+$item_detais_rowss->subtotal_on_product;
                    if($demo_row->item_id == $item_detais_rowss->item_id) {
                        $item = optional($item_detais_rowss->item)->name ?? "";
                        array_push($ledger_output, $item);
                    }
                }
                $total_price = $subtotal_price + $purchases_row->other_bill -
                $purchases_row->discount_total;
            ?>
            {!!implode("<br style='mso-data-placement:same-cell;' />", $ledger_output)!!}
            </td>
            <td>
                <?php $total_qty += $demo_row->qty; ?>
                {{ $demo_row->qty }}
            </td>
            <td>
                {{ number_format($demo_row->price, 2) }} 
            </td>
            <td>
                {{ number_format(($demo_row->qty ?? 0) * ($demo_row->price ?? 0), 2)}} 
            </td>
        </tr>
        @endforeach

        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <td>{{ number_format($total_qty, 2) }}  </td>
            <td colspan="2">{{ number_format($total_amount, 2) }} Tk. </td>
        </tr>
    </tfoot>
</table>