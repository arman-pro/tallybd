<?php
    $company = App\Companydetail::first();
    $i = 0;
    $all_total_pur_price = 0;
    $all_total_sales_price = 0;
    $all_total_qty = 0;
    $prevousStockSumTotal = 0;
    $prevousStockSumCount = 0;
    $item = App\Item::where('id', request()->item_id)
        ->with(['stocks' => function($stock){
            $stock->whereBetween('date', [request()->fromDate, request()->toDate])->orderBy('date');
        }])
    ->first();
    $prevousStock = App\Item::where('id', request()->item_id)
        ->with(['stocks' => function($stock){
            $stock->where('date', '<', request()->fromDate);
        }])
    ->first();
    if(count($prevousStock->stocks)> 0){
        $prevousStockSumTotal = $prevousStock->stocks->sum('total_average_price');
        $prevousStockSumCount = $prevousStock->stocks->sum('total_qty');
    
    }
?>
<table>
    <thead>
        <tr>
            <td colspan="9">{{$company->company_name}}</td>
        </tr>
        <tr>
            <td colspan="9">{{$company->company_address}}</td>
        </tr>
        <tr>
            <td colspan="9">Item Wise Stock</td>
        </tr>
        <tr>
            <td colspan="9">From: {{ date('d-m-Y', strtotime(request()->fromDate)) }} - To:{{ date('d-m-Y', strtotime(request()->toDate)) }}</td>
        </tr>
        <tr>
            <td colspan="9">Previous Stock : {{ number_format($prevousStockSumCount, 2)  }}</td>
        </tr>
        @if ($prevousStockSumTotal > 0 && $prevousStockSumCount>0)
        <tr>
            <td colspan="9">Previous Average Price : {{ number_format($prevousStockSumTotal/$prevousStockSumCount, 2)  }}</td>
        </tr>
        <tr>
            <td colspan="9">Previous Total Price : {{ number_format($prevousStockSumTotal, 2)  }}</td>
        </tr>
        @endif
        <tr>
            <th>Date</th>
            <th> Stock Type</th>
            <th> Vch.No</th>
            <th>Godown Id </th>
            <th>In Qty</th>
            <th> Out Qty </th>
            <th> Closing/Qty</th>
            <th>Price </th>
            <th>Total Price </th>
        </tr>
    </thead>
    <?php
         $total_qty = 0;
    ?>
    <tbody>
        @foreach($item->stocks as $i => $item_row)
        <tr>
            <td>{{ date('d-m-Y', strtotime($item_row->date)) }} </td>
            <td>{{ str_replace('App\\', '',$item_row->stockable_type)??0  }} </td>
            <td>{{ $item_row->stockable->product_id_list ?? $item_row->stockable->vo_no ?? "N/A" }} </td>
            <td>{{ optional($item_row->godown)->name??'-'  }} </td>
            <td>{{ $item_row->in_qty == 0?0 : number_format($item_row->in_qty, 2)  }} </td>
            <td>{{ $item_row->out_qty == 0?0 : number_format($item_row->out_qty, 2)  }} </td>
            <?php
                if($i == 0){
                    $total_qty += $prevousStockSumCount;
                }
                $total_qty += $item_row->total_qty;
            ?>
            <td>{{ number_format($total_qty, 2)  }} </td>
            <td>{{ number_format($item_row->average_price, 2)  }} </td>
            @if ($item_row->total_average_price> 0)
                <td>{{ number_format($item_row->total_average_price, 2)  }}</td>
            @else
                <td>{{ number_format($item_row->total_average_price*-1, 2)  }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>        
</table>   