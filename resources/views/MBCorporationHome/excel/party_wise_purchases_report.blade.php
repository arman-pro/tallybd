<?php
    $company = App\Companydetail::first();
    $account = App\AccountLedger::where('id',$account_ledger_id)->first();
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
            <th colspan="7">{{$company->mobile_number}}</th>
        </tr>
        <tr>
            <th colspan="7">Party Name : {{$account->account_name}}</th>
        </tr>
        <tr>
            <th colspan="7">From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}}</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Vo.No</th>
            <th>Account Lager</th>
            <th>Item Details</th>
            <th>Total Qty</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <?php
        $purchases = App\PurchasesAddList::where('account_ledger_id',$account_ledger_id)
                    ->whereBetween('date', [$formDate, $toDate])->get();
        $total_qty= 0;
    ?>
    <tbody>
        @foreach($purchases as $purchases_row)
        <tr>
            <td >{{ date('d-m-Y', strtotime($purchases_row->date)) }}
            </td>
            <td>
                {{$purchases_row->product_id_list}}</td>
            <td>
                {{ optional($purchases_row->ledger)->account_name??'-'}}</td>
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
                        $item = optional($item_detais_row->item)->name ?? "";
                        array_push($output, $item);
                    }
                ?>
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            <td>
                <?php
                    $output = [];
                    foreach($item_detais as $item_detais_row){
                        $total_qty += $item_detais_row->qty;
                        $item = number_format($item_detais_row->qty, 2) ?? "0";
                        array_push($output, $item);
                    }
                ?>
                {!!implode("<br style='mso-data-placement:same-cell;' />", $output)!!}
            </td>
            <td>
                <?php
                    $output = $item_detais->map(function($row) {
                        return number_format($row->price, 2) ?? "0";
                    })->implode("<br style='mso-data-placement:same-cell;' />");
                ?>
               {!!$output!!}
            </td>
            <td>
                <?php
                    $output = $item_detais->map(function($row) {
                        return number_format(($row->price * $row->qty), 2) ?? "0";
                    })->implode("<br style='mso-data-placement:same-cell;' />");
                ?>
                {!!$output!!}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" >Total</th>
            <th>{{number_format($total_qty, 2) }} </th>
            <td>&nbsp;</td>
            <th>{{number_format($purchases->sum('grand_total'), 2) }} Tk. </th>
        </tr>
    </tfoot>
</table>