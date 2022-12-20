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
            <th colspan="7">{{$company->mobile_number}}</th>
        </tr>
        <tr>
            <th colspan="7">Item Wise Purchase</th>
        </tr>
        <tr>
            <th colspan="7">
                From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}}
            </th>
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
        $amount= 0;
        $total_qty= 0;
    ?>
    <tbody>
        @foreach($item->demoProductAddOnVoucher??[] as $demo_row)
        <?php
            $purchases = App\PurchasesAddList::where('product_id_list',$demo_row->product_id_list)->get();
            $grand_total=0;
            $amount += $demo_row->subtotal_on_product;
        ?>
        @foreach($purchases as $purchases_row)
        <tr>
            <td>{{ date('d-m-y', strtotime($purchases_row->date)) }}</td>
            <td>{{$demo_row->product_id_list}}</td>
            <td>{{$purchases_row->ledger->account_name}}</td>
            <td>
                <?php
                    $qty=0;
                    $total_price = 0;
                    $subtotal_price= 0;
                    $item_detais=App\DemoProductAddOnVoucher::where("product_id_list", $purchases_row->product_id_list)
                    ->with('item')->get();
    
                    foreach ($item_detais as $item_detais_rowss) {
                        $qty=$qty+$item_detais_rowss->qty;
                        $subtotal_price=$subtotal_price+$item_detais_rowss->subtotal_on_product;
                    }
                    $total_price = $subtotal_price + $purchases_row->other_bill - $purchases_row->discount_total;
                    
                    $output = [];
                    foreach($item_detais as $item_detais_row) {
                        if($demo_row->item_id == $item_detais_row->item_id){
                            $item = optional($item_detais_row->item)->name ?? "";
                            array_push($output, $item);
                        }
                    }
                    
                ?>
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            <td>
                <?php $total_qty += $demo_row->qty; ?>
                {{  $demo_row->qty }}
            </td>
            <td>
                {{  number_format($demo_row->price, 2) }} 
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
            <td colspan="2">{{ number_format($amount, 2) }} Tk. </td>
        </tr>
    </tfoot>
</table>