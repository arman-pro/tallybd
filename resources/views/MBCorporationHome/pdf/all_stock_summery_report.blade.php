@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "All Stock Summery Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;
        height: 11.7in; */
        margin: 10px;
        padding: 0;
    }
    .content_area {
        /* width: 8.3in;
        height: 11.7in;
        margin: auto;
        border: 1px solid black;
        display: block; */
    }
    
    @page {
        page: a4;
    }

    .pdf-table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }

    .pdf-table tr, .pdf-table  th, .pdf-table  td, .pdf-table thead {
        border: 1px solid black;
        padding: 5px 3px;
    }

    .text-center {
        text-align: center;
    }

    .float-end {
        float: right;
    }

    .float-start {
        float: left;
    }

    .page-break {
        page-break-after: always;
    }
</style>
@endpush

@section('pdf_content')
<div class="container-fluid">
    <?php
        $company = App\Companydetail::first(); 
    ?>
   
    <div class="p-0 content_area" >
        <div>
            <h3 style="font-weight: 800;margin:0;text-align:center;">{{$company->company_name}}</h3>
            <p style="margin:0;text-align:center;">{{$company->company_address}}<br> Call:
                {{$company->mobile_number}}</p>
            <h4 style="margin:0;text-align:center;">Stock Summery Report</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime(request()->fromDate))}} TO : {{date('d-m-Y', strtotime(request()->toDate))}} </strong></p>
            <table class="pdf-table" >
                @php
                $company = App\Companydetail::first();
                @endphp
                <thead>                   
                    <tr style="font-size:14px;font-weight: 800;">
                            <td >SL.NO</td>
                            <td style="text-align: left;">Product Name</td>
                            <td >Av Sales Price</td>
                            <td >Av Purchases Price</td>
                            <td >Qty</td>
                            <td > Total Sales Price</td>
                            <td >Total Purchases Price</td>
                    </tr>
                </thead>

                <tbody>
                    @php
                    $i = 0;
                    $all_total_pur_price = 0;
                    $all_total_sales_price = 0;
                    $all_total_qty = 0;
                    $item = App\Item::get();
                @endphp
                @foreach($item as $i=>$item_row)
                
                @php
                        $histories= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                    'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder', 'App\Production','App\StockTransfer'])
                    ->where('item_id', $item_row->id)
                    ->whereBetween('date',[request()->fromDate, request()->toDate])
                    ->orwhere(function($query)use( $item_row) {
                            $query->whereDate('date','<=', request()->fromDate)->where('item_id', $item_row->id);
                            //$query->whereDate('date','<', request()->fromDate)->where('item_id', $item_row->id);
                        })
                    ->get();
                    
                        if(sizeof($histories)== 0){
                            $histories= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                            'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\StockTransfer'])
                            ->where('item_id', $item_row->id) 
                            ->where('date','<=', request()->fromDate)
                            ->get();
                        }

                        $histories2= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                        'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
                        ->where('item_id', $item_row->id)
                        ->where('date', '<=', request()->toDate)->get();
                        //->where('date', '<', request()->toDate)->get();
                        
                        $totalCount = 0;
                        $total_in = 0;
                        $averagePrice = 0;
                        $totalPrice = 0;
                        $totalPur_Price = 0;
                        foreach ($histories as $key => $history) {
                            
                            if($history->in_qty > 0){
                                $total_in += $history->in_qty;
                                $totalPur_Price += $history->total_average_price;
                            }
                            //$totalCount += $history->total_qty;
                            $totalPrice += $history->total_average_price;
                        }
                        
                        foreach ($histories2 as $key => $history2) {
                            
                            $totalCount += $history2->total_qty; 
                            //$totalPrice += $history2->total_average_price;
                        }
                        
                        if($totalPur_Price > 0 && $total_in > 0){
                            $averagePrice =  ($totalPur_Price / $total_in);
                        }
                        //else{
                         //   $averagePrice = $totalPrice==0?$averagePrice:($totalCount==0?$averagePrice:$totalPrice / $totalCount);
                       // }
                        
                        
                        
                        
                        
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
                                $totalSaleCount += ($sale->out_qty*-1);
                                 
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
                    @endphp
                    
                @if(request()->has('filter') && request()->filter == 'filter' && ($averagePrice*$totalCount) != 0)
                <tr style="font-size:14px;">
                    <td>{{$i+1}}</td>
                    <td style="text-align: left;">
                        {{$item_row->name}}</td>
                    <td>{{
                        number_format(($saleAvg), 2)}}</td>
                    <td>{{
                        number_format($averagePrice, 2)}}</td>
                    <td style="font-size:18px;text-align:center">{{
                        number_format($totalCount, 2)}}</td>
                    <td style="text-align:center">{{
                        number_format($saleAvg*$totalCount , 2)}}</td>
                    <td style="text-align:center">{{
                        number_format($averagePrice*$totalCount, 2)}}</td>
                </tr>
                @endif
                
                @if(request()->has('filter') && request()->filter == 'all')
                <tr style="font-size:14px;">
                    <td>{{$i+1}}</td>
                    <td style="text-align: left;">
                        {{$item_row->name}}</td>
                    <td>{{
                        number_format(($saleAvg), 2)}}</td>
                    <td>{{
                        number_format($averagePrice, 2)}}</td>
                    <td style="font-size:18px;text-align:center">{{
                        number_format($totalCount, 2)}}</td>
                    <td style="text-align:center">{{
                        new_number_format($saleAvg*$totalCount , 2)}}</td>
                    <td style="text-align:center">{{
                        new_number_format($averagePrice*$totalCount, 2)}}</td>
                </tr>
                @endif
                @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="font-size:16px;text-align:right"><strong>{{
                            number_format($all_total_qty, 2)}}</strong></td>
                        <td style="font-size:16px;text-align:right"><strong>{{
                            new_number_format($all_total_sales_price, 2)}}</strong></td>
                        <td style="font-size:16px;text-align:center"><strong>{{
                            new_number_format($all_total_pur_price, 2)}}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection