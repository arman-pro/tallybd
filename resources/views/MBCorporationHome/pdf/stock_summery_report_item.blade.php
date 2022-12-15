@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Item Wise Stock Summery Report")

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

    <?php
                                
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
   
    <div class="p-0 content_area" >
        <div>
            <h3 style="font-weight: 800;margin:0;text-align:center;">{{$company->company_name}}</h3>
            <p style="margin:0;text-align:center;">{{$company->company_address}}<br> Call:
                {{$company->mobile_number}}</p>
            <h4 style="margin:0;text-align:center;">Item Wise Stock Report</h4>
            <h4> {{ $item->name??' ' }}</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime(request()->fromDate))}} To : {{date('d-m-Y', strtotime(request()->toDate))}} </strong></p>
            <h5 style="margin:0;text-align:center;"> Previous Stock : {{ number_format($prevousStockSumCount, 2)  }} </h5>
            @if ($prevousStockSumTotal > 0 && $prevousStockSumCount>0)
            <h5 style="margin:0;text-align:center;"> Previous Average Price : {{ number_format($prevousStockSumTotal/$prevousStockSumCount, 2)  }} </h5>
            <h5 style="margin:0;text-align:center;"> Previous Total Price : {{ number_format($prevousStockSumTotal, 2)  }} </h5>
            @endif
            <table class="pdf-table">
                
                <thead>
                    <tr style="font-size:14px;font-weight: 800;">
                        <th style="text-align: center;">Date</th>
                        <th > Stock Type</th>
                        <th > Vch.No</th>
                        <th >Godown Id </th>
                        <th >In Qty</th>
                        <th > Out Qty </th>
                        <th style="font-size:18px;"> Closing/Qty</th>
                        <th >Price </th>
                        <th >Total Price </th>
                    </tr>
                </thead>


                @php
                      $total_qty = 0;
                @endphp
                <tbody>
                    @foreach($item->stocks as $i => $item_row)
                    <tr style="font-size:14px;">
                        <td >{{ date('d-m-Y', strtotime($item_row->date)) }} </td>
                        <td >{{ str_replace('App\\', '',$item_row->stockable_type)??0  }} </td>
                         <td >{{ $item_row->stockable->product_id_list ?? $item_row->stockable->vo_no ?? "N/A" }} </td>
                        <td >{{ optional($item_row->godown)->name??'-'  }} </td>
                        <td >{{ $item_row->in_qty == 0?0 : number_format($item_row->in_qty, 2)  }} </td>
                        <td >{{ $item_row->out_qty == 0?0 : number_format($item_row->out_qty, 2)  }} </td>
                        @php
                        if($i == 0){
                          /// if($prevousStockSumCount > 0 ) {
                            //$total_qty += $ prevousStockSumCount;
                          // }else {
                           //     $total_qty -= $prevousStockSumCount;
                           //} 
                            $total_qty += $prevousStockSumCount;
                        }
                        $total_qty += $item_row->total_qty;
                        @endphp

                        <td style="font-size:18px;">{{ number_format($total_qty, 2)  }} </td>
                        <td >{{ number_format($item_row->average_price, 2)  }} </td>
                        @if ($item_row->total_average_price> 0)

                        <td >{{ number_format($item_row->total_average_price, 2)  }} </td>
                        @else
                        <td >{{ number_format($item_row->total_average_price*-1, 2)  }} </td>

                        @endif
                    </tr>
                    @endforeach
                </tbody>        
            </table>
        </div>
    </div>
</div>

@endsection