@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Stock Summery Report")

@push('css')
<style type="text/css">
    .table-borderless > thead > tr > th {
        border: none;
    }
    .table-borderless > thead > tr > td {
        border: 1px solid gray ;
    }
    .table-borderless > tbody > tr > td {
        border: 1px solid gray ;
    }
    .table-borderless > tfoot > tr > td {
        border: 1px solid gray ;
    }
    table, td, th {
      border: 1px solid #000;
    }
    
    table { 
      border-collapse: collapse;
    }
</style>
@endpush


@section('admin_content')
<div class="container-fluid" >
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body overflow-auto">
                    <a class="btn-info btn btn-lg fw-bold text-light" href="{{route('all_stock_summery_report')}}">Stock Summery</a>
                    <a class="btn-success btn btn-lg fw-bold text-light"href="{{route('stock_summery_report_catagory_search_from')}}">Catagory Wise Stock Summery</a>
                    <a class="btn-primary btn btn-lg fw-bold text-light"href="{{route('stock_summery_report_godown_search_from')}}">Godown Wise Stock Summery</a>
                    <a class="btn-danger btn btn-lg fw-bold text-light" href="{{url('stock_summery_report_item_search_from')}}">Item Wise Stock Summery</a>
                </div>
            </div>
        </div>

        {{-- search form --}}
        <div class="col-sm-12">
            <form action="{{url('/all_stock_summery_report')}}" method="GET">
                <input type="hidden" name="search" value="1">
            <div class="card">
                <div class="card-header bg-info text-light">
                    <h4 class="card-title">Stock Summery Report</h4>
                </div>
                <div class="card-body">                    
                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">From</label>
                            <input type="Date" class="form-control" name="fromDate" value="{{ date('Y-m-d') }}" required="" value="{{ request()->fromDate??' ' }}" />
                        </div>    
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">To</label>
                            <input type="Date" class="form-control" name="toDate"  value="{{ date('Y-m-d') }}"required="" value="{{ request()->toDate??' ' }}" />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="cono1" class="control-label col-form-label">Filter</label>
                            <select name="filter" class="form-control">
                                <option value="" hidden>Select Filter</option>
                                <option value="all" selected >All</option>
                                <option value="filter" >Filter</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-info btn-lg text-light"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>
        @if(request()->search)
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-info text-light">
                    <h4 class="card-title">All Stock Summery Report</h4>
                </div>
                <div class="card-body overflow-auto">
                    <div class="col-md-12" id="main_table">
                        <div>
                        @if(request()->toDate && request()->fromDate)
                        <table class="table table-borderless" id="printArea" >
                            @php
                            $company = App\Companydetail::first();
                            @endphp
                            <thead>
                                <tr>
                                    <th  colspan="8" style="text-align: center;Color;Black; border:0px !important;">
                                        <h3 styly="font-weight: 800;Color:Black;font-family:Calisto MT;margin:0">{{$company->company_name}}</h3>
                                    {{$company->company_address}}, Tel: {{$company->phone}}, Call:
                                            {{$company->mobile_number}}<br>
                                            Stock Summery Report <br>
                                            From: {{ date('d-m-Y', strtotime(request()->fromDate)) }} - To:{{ date('d-m-Y', strtotime(request()->toDate)) }}<br>
                                    </th>
                                </tr>
                               
                                <tr style="font-size:14px;font-weight: 800;Color;Black;">
                                        <td style="padding: 5px 5px;width: 50px;">SL.NO</td>
                                        <td style="padding: 5px 5px;width: 250px; text-align: left;">Product Name</td>
                                        <td style="padding: 5px 5px;width: 100px;">Av Sales Price</td>
                                        <td style="padding: 5px 5px;width: 100px;">Av Purchases Price</td>
                                        <td style="padding: 5px 5px;width: 100px; text-align:center">Qty</td>
                                        <td style="padding: 5px 5px;width: 20px; text-align:center">Unit</td>
                                        <td style="padding: 5px 5px;width: 150px;"> Total Sales Price</td>
                                        <td style="padding: 5px 5px;width: 150px;">Total Purchases Price</td>
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
                                    
                                    if($totalPur_Price > 0 && $total_in > 0) {
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
                            <tr style="font-size:14px;Color;Black;">
                                <td style="padding: 5px 5px;">{{$i+1}}</td>
                                <td style="padding: 5px 5px;width: 250px;text-align: left;">
                                    {{$item_row->name}}</td>
                                <td style="padding: 5px 5px;width: 100px;">{{
                                    number_format(($saleAvg), 2)}}</td>
                                <td style="padding: 5px 5px;width: 100px;">{{
                                    number_format($averagePrice, 2)}}</td>
                                <td style="font-size:18px;padding: 5px 5px;width: 100px;text-align:center">{{
                                    number_format($totalCount, 2)}}</td>
                                     <td style="font-size:18px;padding: 5px 5px;width: 100px;text-align:center">{{$item_row->unit->name}}</td>
                                     
                                <td style="padding: 5px 5px;width: 100px;text-align:center">{{
                                    number_format($saleAvg*$totalCount , 2)}}</td>
                                <td style="padding: 5px 5px;width: 100px;text-align:center">{{
                                    number_format($averagePrice*$totalCount, 2)}}</td>
            
                            </tr>
                            @endif
                            
                            @if(request()->has('filter') && request()->filter == 'all')
                            <tr style="font-size:14px;Color;Black;">
                                <td style="padding: 5px 5px;">{{$i+1}}</td>
                                <td style="padding: 5px 5px;width: 250px;text-align: left;">
                                    {{$item_row->name}}</td>
                                <td style="padding: 5px 5px;width: 100px;">{{
                                    number_format(($saleAvg), 2)}}</td>
                                <td style="padding: 5px 5px;width: 100px;">{{
                                    number_format($averagePrice, 2)}}</td>
                                    
                                <td style="font-size:18px;padding: 5px 5px;width: 100px;text-align:center"> <strong>{{
                                    number_format($totalCount, 2)}} </strong></td>
                                    
                                     <td style="font-size:15px;padding: 5px 5px;width: 20px;text-align:center">{{$item_row->unit->name}} </td>
                                    
                                <td style="padding: 5px 5px;width: 100px;text-align:center">{{
                                    new_number_format($saleAvg*$totalCount , 2)}}</td>
                                <td style="padding: 5px 5px;width: 100px;text-align:center">{{
                                    new_number_format($averagePrice*$totalCount, 2)}}</td>
            
                            </tr>
                            @endif
                            @endforeach
            
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" style="font-size:16px;padding: 5px 5px;width: 100px;text-align:right"><strong>{{
                                        number_format($all_total_qty, 2)}}</strong></td>
                                         <td style="font-size:16px;padding: 5px 5px;width: 100px;text-align:right"><strong>--</strong></td>
                                    <td style="font-size:16px;padding: 5px 5px;width: 100px;text-align:right"><strong>{{
                                        new_number_format($all_total_sales_price, 2)}}</strong></td>
                                    <td style="font-size:16px;padding: 5px 5px;width: 100px;text-align:center"><strong>{{
                                        new_number_format($all_total_pur_price, 2)}}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-lg btn-info text-light"  onclick="printData()"><i class="fa fa-print"></i> Print</button>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
                    <a href="{{url()->full()}}&excel=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
@push('js')
<script>
    function printData()
    {
        var divToPrint = document.getElementById('printArea');
        var htmlToPrint = '' +
            '<style type="text/css">' +
            'table th, table td {' +
            'border:1px solid #000;' +
            '}' +
            'table{'+
            'border-collapse: collapse;'+
            '}'+
            '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin = window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();

    }
    </script>
@endpush