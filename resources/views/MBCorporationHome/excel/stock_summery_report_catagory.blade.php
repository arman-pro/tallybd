<?php
    $company = App\Companydetail::first();
?>
<table>
    <thead>
        <tr>
            <th colspan="6">{{$company->company_name}}</th>
        </tr>
        <tr>
            <th colspan="6">{{$company->company_address}}</th>
        </tr>
        <tr>
            <th colspan="6">Catagory Wise Stock Summery</th>
        </tr>
        <tr>
            <th colspan="6">From: {{ date('d-m-Y', strtotime(request()->fromDate)) }} - To:{{ date('d-m-Y', strtotime(request()->toDate)) }}</th>
        </tr>
        <tr>
            <td>Product Name</td>
            <td>Av Sales Price</td>
            <td>Av Purchases Price</td>
            <td>Qty</td>
            <td> Total Sales Price</td>
            <td>Total Purchases Price</td>
        </tr>
    </thead>
    <tbody>
    <?php
        $i = 0;
        $all_total_pur_price = 0;
        $all_total_sales_price = 0;
        $all_total_qty = 0;
        $item = App\Item::whereCategoryId(request()->category_id)->get();
    ?>
    @foreach($item as $i=>$item_row)
    <tr>
        <?php
            $histories= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
            'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder', 'App\Production'])
            ->where('item_id', $item_row->id)
            ->whereBetween('date',[request()->fromDate, request()->toDate])
            ->orwhere(function($query)use( $item_row) {
                    $query->whereDate('date','<', request()->fromDate)->where('item_id', $item_row->id);
                })
            ->get();

            if(sizeof($histories)== 0){
                $histories= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment'])
                ->where('item_id', $item_row->id)
                ->where('date','<', request()->fromDate)
                ->get();
            }

            $totalCount = 0;
            $averagePrice = 0;
            $totalPrice = 0;
            
            $total_inqty = 0;
            $total_inqtyP = 0;
        
            foreach ($histories as $key => $history) {
                $totalCount += $history->total_qty;
                $totalPrice += $history->total_average_price;
                
                if($history->in_qty >0){
                    $total_inqty += $history->in_qty;
                    $total_inqtyP += $history->total_average_price;
                }
            }
            
            if($total_inqtyP>0 && $total_inqty>0){
                $averagePrice=  ($total_inqtyP / $total_inqty);
            }


            $salehistories= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                    'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment',
                    'App\WorkingOrder','App\Production'])
            ->where('item_id', $item_row->id)
            ->orwhere(function($query)use( $item_row) {
                $query->whereDate('date','<', request()->fromDate)->where('item_id', $item_row->id);
            })
            ->whereBetween('date',[request()->fromDate, request()->toDate])->get();
            $totalSaleCount = 0;
            $saleAvg = 0;
            $totalSalePrice = 0;
            $total_out=0;
            $total_Sale_Price=0;
                
            if( sizeof($salehistories) != 0){
                foreach ($salehistories as $key => $sale) {
                    $totalSaleCount += ($sale->total_qty*-1);
                    $totalSalePrice += ($sale->total_average_price *-1);
                    
                    $total_out += $sale->out_qty;
                    if($sale->out_qty >0){
                        $total_Sale_Price += ($sale->total_average_price *-1);
                    }
                }

                if( $total_Sale_Price > 0 &&  $total_out > 0){
                    $saleAvg =  ($total_Sale_Price / $total_out) ;
                }
            }
            $all_total_qty += $totalCount;
            $all_total_pur_price += $averagePrice*$totalCount;
            $all_total_sales_price += $saleAvg*$totalCount;
        ?>
        <td>
            {{$item_row->name}}</td>
        <td>{{number_format(($saleAvg), 2)}}</td>
        <td>{{number_format($averagePrice, 2)}}</td>
        <td>{{number_format($totalCount, 2)}}</td>
        <td>{{number_format($saleAvg*$totalCount , 2)}}</td>
        <td>{{number_format($averagePrice*$totalCount, 2)}}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total</td>
            <td>{{number_format($all_total_qty, 2)}}</td>
            <td>{{number_format($all_total_sales_price, 2)}}</td>
            <td>{{number_format($all_total_pur_price, 2)}}</td>
        </tr>
    </tfoot>
</table>