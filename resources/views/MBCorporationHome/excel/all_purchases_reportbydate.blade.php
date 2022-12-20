<?php
    $company = App\Companydetail::first();
?>
<table>
    <thead>
        <tr>
            <th colspan="7">
                {{$company->company_name}}
            </th>
        </tr>
        <tr>
            <th colspan="7">
                Address: {{$company->company_address}}
            </th>
        </tr>
        <tr>
            <th colspan="7">
                All Purchase
            </th>
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
            <th>Total Price(TK)</th>
        </tr>
    </thead>
    <?php
        $purchases = App\PurchasesAddList::whereBetween('date',[$formDate,$toDate])->orderBy('date')->get();
    ?>
    <tbody>
        <?php
            $total_price__ = 0;
            $total_qty__= 0;
          ?>
          @foreach($purchases as $purchases_row)
     <tr>
         <td >{{ date('d-m-y', strtotime($purchases_row->date)) }}
         </td>
         <td>
             {{$purchases_row->product_id_list}}</td>
         <td>
             {{ optional($purchases_row->ledger)->account_name??'-'}}</td>
         <td >
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
                
                $output = $item_detais->map(function($item_detail_row) {
                    return optional($item_detail_row->item)->name?? "N/A";
                })->implode("<br style='mso-data-placement:same-cell;' />");
            ?>
            {!!$output!!}
         </td>

         <td>
            <?php
                $output = $item_detais->map(function($item_detail_row) {
                    return number_format($item_detail_row->qty, 2) ?? "0";
                })->implode("<br style='mso-data-placement:same-cell;' />");
            ?>
            {!!$output!!}
         </td>
         <td>
            <?php
                $output = $item_detais->map(function($item_detail_row) {
                    return number_format($item_detail_row->price, 2) ?? "0";
                })->implode("<br style='mso-data-placement:same-cell;' />");
            ?>
            {!!$output!!}
         </td>
         <td>
            <?php
                $output = $item_detais->map(function($row)use($total_price__,$total_qty__){
                    $total_price__ += ($row->price * $row->qty);
                    $total_qty__ += ($row->qty);
                    return new_number_format(($row->price * $row->qty) , 2);
                })->implode("<br style='mso-data-placement:same-cell;' />");
            ?>
            @foreach($item_detais as $row)
                <?php
                    $total_price__ += ($row->price * $row->qty);
                    $total_qty__ += ($row->qty);
                ?>
            @endforeach
            {!!$output!!}
         </td>
     </tr>
     @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;font-weight:bold">{{  number_format($total_qty__, 2) }}</td>
            <td colspan="2" style="text-align: right;font-weight:bold">{{ new_number_format($total_price__, 2) }} .TK</td>
        </tr>
    </tfoot>
</table>