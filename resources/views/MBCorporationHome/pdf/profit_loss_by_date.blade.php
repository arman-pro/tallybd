@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Profit Loos Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;*/
        /*height: 11.7in; */
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
    
    /*@page {*/
    /*    page: a4;*/
    /*    margin: 0.2in;*/
    /*}*/

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
            <h4 style="margin:0;text-align:center;">Profit & Loss Report</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime($fromDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong></p>
            <table class="pdf-table">
                <tbody>
                    <tr style="font-size:14px;font-weight: 800;">
                            <td>Particulars</td>
                            <td>Particulars</td>
                    </tr>
                    <tr style="font-size:14px;">
                        <td>
                            <table style="text-align: center;" class="pdf-table">

                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        Opening Stock</td>
                                    <td style="text-align: right;">
                                        @php
                                            $item = App\Item::get();
                                            $opening_total_pur_price = 0;
                                            foreach($item as $i=>$item_row){
                                                $opening= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
                                                ->where('item_id', $item_row->id)
                                                ->where('date', '<', $fromDate)->get();
                                                $totalCount = 0;
                                                $total_in = 0;
                                                $averagePrice = 0;
                                                $totalPrice = 0;
                                                $totalPur_Price = 0;
                                                foreach ($opening as $key => $history) {
                                                    $total_in += $history->in_qty;
                                                    if($history->in_qty >0){
                                                        $totalPur_Price += $history->total_average_price;
                                                    }
                                                    $totalCount += $history->total_qty;
                                                    $totalPrice += $history->total_average_price;
                                                }

                                                if($totalPur_Price>0 && $total_in>0){
                                                    $averagePrice =  ($totalPur_Price / $total_in);
                                                }
                                                // change by arman
                                                //else{
                                                //    $averagePrice =  $totalPrice==0?$averagePrice:($totalPrice / $totalCount);
                                                //}
                                                $opening_total_pur_price += $averagePrice*$totalCount;
                                            }
 
                                           echo  new_number_format($opening_total_pur_price??0, 2)
                                        @endphp

                                    </td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        Purchases Value</td>
                                    <td style="text-align: right;">
                                        {{ new_number_format($totalPurchase??0, 2) }}
                                    </td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        Sales Return Value</td>
                                    <td style="text-align: right;">
                                        {{ number_format($totalReturnSale??0, 2) }}

                                    </td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;"> Expenses</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @foreach ($expenseGroup as $Exgroup)
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td >{{ $Exgroup['name']??" " }} = {{ new_number_format( $Exgroup['amount'] ??0 , 2) }}</td>
                                    <td>0.00</td>
                                </tr>
                                @endforeach
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        &nbsp;</td>
                                    <td style="text-align: right;">
                                        {{ new_number_format($expenses??0, 2) }}
                                    </td>
                                </tr>


                            </table>
                        </td>
                        <td >
                            <table class="pdf-table">
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        Total Present Stock Value</td>
                                    <td style="text-align: right;">
                                        
                                        @php
                                             
                                            $present_total_pur_price = 0;
                                            foreach($item as $i=>$item_row){
                                                $present_stock= App\StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
                                                ->where('item_id', $item_row->id)
                                                // this is change by arman
                                                ->where('date', '<=', $toDate)->get();
                                               // ->where('date', '<', $toDate)->get();
                                                $totalCount = 0;
                                                $total_in = 0;
                                                $averagePrice = 0;
                                                $totalPrice = 0;
                                                $totalPur_Price = 0;
                                                foreach ($present_stock as $key => $history) {
                                                    $total_in += $history->in_qty;
                                                    if($history->in_qty >0){
                                                        $totalPur_Price += $history->total_average_price;
                                                    }
                                                    $totalCount += $history->total_qty;
                                                    $totalPrice += $history->total_average_price;
                                                }

                                                if($totalPur_Price>0 && $total_in>0){
                                                    $averagePrice =  ($totalPur_Price / $total_in);
                                                }
                                                
                                                // this is change by arman
                                                /**else{
                                                    $averagePrice =  $totalPrice==0?$averagePrice:($totalPrice / $totalCount);
                                                }**/
                                                
                                                $present_total_pur_price += $averagePrice*$totalCount;
                                            }
 
                                           echo  new_number_format($present_total_pur_price??0, 2)
                                        @endphp
                                    </td>
                                </tr>

                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style=";text-align: left;">
                                        Sales Value</td>
                                    <td style="text-align: right;">
                                        {{ new_number_format($totalSale*-1??0, 2) }}

                                    </td>
                                </tr>

                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        Purchases Return Value</td>
                                    <td style="text-align: right;">
                                        {{ number_format($totalReturnPurchase*-1??0, 2) }}
                                    </td>
                                </tr>

                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        Income
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;">
                                @foreach ($incomeGroup as $Ingroup)
                                    <tr style="font-size:14px;font-weight: 700;">
                                        <td style="text-align:center">{{ $Ingroup['name']??" " }} = {{ new_number_format($Ingroup['amount']?? 0, 2 ) }}</td>
                                        <td style="text-align:start">0.00</td>
                                    </tr>
                                @endforeach
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="text-align: left;">
                                        &nbsp;</td>
                                    <td style="text-align: right;">
                                        {{ new_number_format($income??0, 2) }}
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    @php

                    $leftSide = 0;
                    $rightSide= 0;
                    if($opening_total_pur_price > 0){
                        $leftSide = $opening_total_pur_price+$totalPurchase+$totalReturnSale+$expenses;

                    }else{
                        $leftSide = $totalPurchase+$totalReturnSale+$expenses;
                    }
                    if($present_total_pur_price >0){
                        $rightSide += $present_total_pur_price;
                        $rightSide+= abs($totalSale);
                        $rightSide+= ($totalReturnPurchase*-1);
                        $rightSide+= abs($income);

                    }else{
                        $rightSide+= abs($totalSale);
                        $rightSide+= ($totalReturnPurchase*-1);
                        $rightSide+= abs($income);
                    }
                    @endphp
                    <tr style="font-size:16px;font-weight: 800;">
                        @if ($leftSide > 0)
                        <td style="text-align: right;">
                            Total : {{new_number_format($leftSide??0 , 2)}} </td>
                        @else
                        <td style="text-align: right;">
                            Total : {{new_number_format(0 , 2)}} </td>
                        @endif
                        
                        <td style="text-align: right;">Total :
                            {{new_number_format($rightSide, 2)}}
                        </td>
                        

                    </tr>
                </tbody>
                <tfoot>
                    <tr style="font-size:16px;font-weight: 800;">
                        @if($leftSide > $rightSide)

                        <td >
                        </td>
                        <td colspan="1" >Loss :
                            {{ new_number_format($leftSide - $rightSide, 2) }}
                        </td>
                        @else
                        <td colspan="1" >
                            Profit : {{ new_number_format($rightSide - $leftSide , 2)}}
                        </td>
                        <td ></td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection