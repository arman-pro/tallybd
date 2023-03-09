@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;"> s Stock Summery Report
    </h3>
    <div class="row">
       <style type="text/css">
        table, td, th {
      border: 1px solid #000;
    }
    
    table { 
      border-collapse: collapse;
    }
            .topnav {
            overflow: hidden;
            background-color: #eee;
        }

            .topnav a {
                width: 25%;
                float: left;
                color: #000;
                text-align: center;
                padding: 5px 16px;
                text-decoration: none;
                font-size: 17px;
                border-radius: 10%
            }

            .topnav a:hover {
                background-color: #ddd;
                color: black;
            }

            .topnav a.active {
                color: greenyellow;
            }
        </style> 
        <div class="col-md-12">
            <div class="topnav">
                <a class="btn-info" href="{{route('all_stock_summery_report')}}">Stock Summery</a>
                <a class="btn-success"href="{{route('stock_summery_report_catagory_search_from')}}">Catagory Wise Stock Summery</a>
                <a class="btn-primary"href="{{route('stock_summery_report_godown_search_from')}}">Godown Wise Stock Summery</a>
                <a class="btn-danger" href="{{url('stock_summery_report_item_search_from')}}">Item Wise Stock Summery</a>
            </div>
        </div>

        <br>
        <br>

        <div class="col-md-12" style="margin: 2%;" >


            <table class="table" id="example">
                @php
                $company = App\Companydetail::first();
                @endphp
                <thead>
                  <tr>
                    <th  colspan="7" style="text-align: center;Color:Black;">{{$company->company_name}}</th>
                  </tr>
                  <tr>
                    <th colspan="7" style="text-align: center;">
                        {{$company->company_address}}, Tel: {{$company->phone}}, Call:
                            {{$company->mobile_number}}
                    </th>
                  </tr>
                  <tr>
                    <th  colspan="7" style="text-align: center;">
                        Stock Summery Report
                    </th>
                  </tr>
                
                  <tr>
                    <th  colspan="7" style="text-align: center;">
                        From: {{ date('d-m-Y', strtotime(request()->fromDate)) }} - To:{{ date('d-m-Y', strtotime(request()->toDate)) }}
                    </th>
                  </tr>
                  <tr style="font-size:14px;font-weight: 800;">
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">SL.NO</td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px; text-align: left;">Product Name</td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Av Sales Price</td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Av Purchases Price</td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Qty</td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"> Total Sales Price</td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total Purchases Price</td>
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
                <tr style="font-size:14px;">
                    @php
                        $histories= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                        'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment'])
                        ->where('item_id', $item_row->id)
                        ->whereBetween('date',[request()->fromDate, request()->toDate])
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

                        foreach ($histories as $key => $history) {
                            $totalCount += $history->total_qty;
                            $totalPrice += $history->total_average_price;
                        }
                        
                        if($totalPrice>0 && $totalCount>0){
                            $averagePrice=  ($totalPrice / $totalCount);
                        }


                        $salehistories= App\StockHistory::whereIn('stockable_type', ['App\SalesAddList','App\SalesReturnAddList'])
                        ->where('item_id', $item_row->id)
                        ->whereBetween('date',[request()->fromDate, request()->toDate])->get();

                        $totalSaleCount = 0;
                        $saleAvg = 0;
                        $totalSalePrice = 0;
                        $total_out=0;
                        totalSPrice=0;
                        if( sizeof($salehistories) != 0){
                            foreach ($salehistories as $key => $sale) {
                                $totalSaleCount += ($sale->total_qty*-1);
                                $totalSalePrice += ($sale->total_average_price *-1);
                                if($sale->out_qty >0){
                                    $total_out += $sale->out_qty;
                                    $totalSPrice += $sale->total_average_price;
                                }
                            }
                            if( $totalSPrice>0 &&  $total_out>0){
                                $saleAvg =  ($totalSPrice / $total_out) ;
                            }
                            
                        }else{

                            $salehistories= App\StockHistory::whereIn('stockable_type', ['App\Item'])
                            ->where('total_qty','>', 0)
                            ->where('item_id', $item_row->id)
                            ->with('item')
                            ->whereBetween('date',[request()->fromDate, request()->toDate])->get();
                            if(sizeof($salehistories)== 0){
                                $salehistories= App\StockHistory::whereIn('stockable_type', ['App\Item'])
                                ->where('item_id', $item_row->id)
                                ->where('date','<', request()->fromDate)
                                ->get();
                            }

                            foreach ($salehistories as $key => $sale) {
                                $totalSaleCount += ($sale->total_qty);

                                $totalSalePrice += ($sale->item->sales_price * $sale->total_qty);
                            }
                            
                            if( $totalSalePrice>0 &&  $totalSaleCount>0){
                                $saleAvg =  ($totalSalePrice / $totalSaleCount) ;

                            }

                        }
                        $all_total_qty += $totalCount;
                        $all_total_pur_price += $averagePrice*$totalCount;
                        $all_total_sales_price += $saleAvg*$totalCount;
                    @endphp

                    <td style="border-right: 1px solid #eee;padding: 5px 5px;">{{$i+1}}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
                        {{$item_row->name}}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{
                        number_format(($saleAvg), 2)}}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{
                        number_format($averagePrice, 2)}}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align:right">{{
                        number_format($totalCount, 2)}}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align:right">{{
                        number_format($saleAvg*$totalCount , 2)}}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align:right">{{
                        number_format($averagePrice*$totalCount, 2)}}</td>


                </tr>
                @endforeach
                  
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align:right"><strong>{{
                            number_format($all_total_qty, 2)}}</strong></td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align:right"><strong>{{
                            number_format($all_total_sales_price, 2)}}</strong></td>
                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align:right"><strong>{{
                            number_format($all_total_pur_price, 2)}}</strong></td>
                    </tr>
                </tfoot>
            </table>

        </div>

    </div>
</div>

@endsection
