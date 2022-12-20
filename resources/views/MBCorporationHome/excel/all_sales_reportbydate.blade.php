<?php
    $company = App\Companydetail::first();
    $purchases = App\SalesAddList::whereBetween('date',[$formDate,$toDate])->get();
?>
<table>
    <thead>
        <tr>
            <th colspan="7">{{$company->company_name}}</th>
        </tr>
        <tr>
            <th colspan="7">{{$company->company_address}}}</th>
        </tr>
        <tr>
            <th colspan="7">{{$company->phone}}</th>
        </tr>
        <tr>
            <th colspan="7">All Sales</th>
        </tr>
        <tr>
            <th colspan="7">From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}}</th>
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
    <tbody>
        <?php
            $total_price__ = 0;
            $total_qty__ =0;
        ?>
        @foreach($purchases as $purchases_row)
       
        <tr>
            <td>{{ date('d-m-Y', strtotime($purchases_row->date)) }}</td>
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

                $output = [];
                $qty_output = [];
                $price_output = [];
                foreach ($item_detais as $item_detais_rowss) {
                    $qty=$qty+$item_detais_rowss->qty;
                    $subtotal_price=$subtotal_price+$item_detais_rowss->subtotal_on_product;
                    $item = optional($item_detais_rowss->item)->name ?? 'N/A';
                    array_push($output, $item);
                    $qty_item = number_format($item_detais_rowss->qty, 2) ?? "0";
                    array_push($qty_output, $qty_item);
                    $price_item = number_format($item_detais_rowss->price, 2) ?? "0";
                    array_push($price_output, $price_item);
                }
                $total_price = $subtotal_price + $purchases_row->other_bill - $purchases_row->discount_total;
    
            ?>
                {!!implode("<br style='mso-data-placement:same-cell;' />", $output)!!}
            </td>
    
            <td>{!!implode("<br style='mso-data-placement:same-cell;' />", $qty_output)!!}</td>
            <td>{!!implode("<br style='mso-data-placement:same-cell;' />", $price_output)!!}</td>
            <td>
            <?php
                $output = [];
                foreach($item_detais as $row){
                    $total_price__ += ($row->price * $row->qty) ?? 0;
                    $total_qty__ += ($row->qty) ?? 0;
                    $total_item = new_number_format(($row->price * $row->qty) , 2);
                    array_push($output, $total_item);
                }
             ?>
             {!!implode("<br style='mso-data-placement:same-cell;' />", $output)!!}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
             <td colspan="5" >{{  number_format($total_qty__, 2) }} </td>
            <td colspan="2" >{{ new_number_format($total_price__, 2) }} Tk </td>
        </tr>
    </tfoot>
    </table>