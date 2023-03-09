@extends('MBCorporationHome.apps_layout.layout')
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
<h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Profit & Loss By Date
</h3>
<div style="background: #fff;" id="printArea">

    <div class="row">


        @php
        $company = App\Companydetail::first();
        @endphp
        <div class="col-md-12" style="" >


            <table cellspacing='0' class="table table-borderless" style="width: 100%">
                <thead>
                    <tr>
                        <th  colspan="7" style="text-align: center;Color;Black; border:0px !important;">
                            <h3 styly="font-weight: 800;font-family:Calisto MT;Color:Black;margin:0">{{$company->company_name}}</h3>
                        {{$company->company_address}}<br> {{$company->phone}} Call:
                                {{$company->mobile_number}}<br>
                                Profit & Loss By Date <br>
                                From: {{ date('d-m-Y', strtotime($fromDate)) }} - To:{{ date('d-m-Y', strtotime($toDate)) }} <br>
                        </th>
                    </tr>
                    

                </thead>
                <tbody>
                    <tr style="font-size:14px;font-weight: 800;Color:Black">
                            <td style="border: 1px solid black;padding: 5px 5px">Particulars</td>
                            <td style="border: 1px solid black;padding: 5px 5px">Particulars</td>
                    </tr>
                    <tr style="font-size:14px;">
                        <td style="border: 1px solid black;padding: 5px 5px;">
                            <table style="text-align: center;width: 100%">

                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        Opening Stock</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
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
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        Purchases Value</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ new_number_format($totalPurchase??0, 2) }}
                                    </td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        Sales Return Value</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ number_format($totalReturnSale??0, 2) }}

                                    </td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;"> Expenses</td>
                                </tr>
                                @foreach ($expenseGroup as $Exgroup)
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 30%;">{{ $Exgroup['name']??" " }} = {{ new_number_format( $Exgroup['amount'] ??0 , 2) }}</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align:start">0.00</td>
                                </tr>
                                @endforeach
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        &nbsp;</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ new_number_format($expenses??0, 2) }}
                                    </td>
                                </tr>


                            </table>
                        </td>
                        <td style="border: 1px solid black;padding: 5px 5px;">
                            <table style="width: 100%">
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 50%;text-align: left;">
                                        Total Present Stock Value</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        
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

                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        Sales Value</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ new_number_format($totalSale*-1??0, 2) }}

                                    </td>
                                </tr>

                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        Purchases Return Value</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ number_format($totalReturnPurchase*-1??0, 2) }}
                                    </td>
                                </tr>

                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        Income
                                    </td>
                                </tr>
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                @foreach ($incomeGroup as $Ingroup)
                                    <tr style="font-size:14px;font-weight: 700;Color:Black">
                                        <td style="padding: 5px 5px;width: 30%;text-align:center">{{ $Ingroup['name']??" " }} = {{ new_number_format($Ingroup['amount']?? 0, 2 ) }}</td>
                                        <td style="padding: 5px 5px;width: 30%;text-align:start">0.00</td>
                                    </tr>
                                @endforeach
                                <tr style="font-size:14px;font-weight: 700;Color:Black">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        &nbsp;</td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
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
                    <tr style="font-size:16px;font-weight: 800;Color:Black">
                        @if ($leftSide > 0)
                        <td style="padding: 5px 5px;width: 100px;text-align: right;">
                            Total : {{new_number_format($leftSide??0 , 2)}} </td>
                        @else
                        <td style="padding: 5px 5px;width: 100px;text-align: right;">
                            Total : {{new_number_format(0 , 2)}} </td>
                        @endif
                        
                        <td style="padding: 5px 5px;width: 100px;text-align: right;">Total :
                            {{new_number_format($rightSide, 2)}}
                        </td>
                        

                    </tr>
                </tbody>
                <tfoot>
                    <tr style="font-size:16px;font-weight: 800;Color:Black">
                        @if($leftSide > $rightSide)

                        <td style="padding: 5px 5px;width: 100px;">
                        </td>
                        <td colspan="1" style="padding: 5px 5px;width: 100px;">Loss :
                            {{ new_number_format($leftSide - $rightSide, 2) }}
                        </td>
                        @else
                        <td colspan="1" style="padding: 5px 5px;width: 100px;">
                            Profit : {{ new_number_format($rightSide - $leftSide , 2)}}
                        </td>
                        <td style="padding: 5px 5px;width: 100px;"></td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
<div class="text-center card-footer">
    <button class="btn btn-lg btn-success text-light fw-bold "  onclick="printData()"><i class="fa fa-print"></i> Print</button>
    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
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
