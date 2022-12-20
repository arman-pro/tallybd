@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Item Wise Stock Summery")

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
      width: 100%;
    }
</style>
@endpush

@section('admin_content')
<div class="container-fluid">
   
    <div class="row">
       
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn-info btn btn-lg fw-bold text-light" href="{{route('all_stock_summery_report')}}">Stock Summery</a>
                    <a class="btn-success btn btn-lg fw-bold text-light"href="{{route('stock_summery_report_catagory_search_from')}}">Catagory Wise Stock Summery</a>
                    <a class="btn-primary btn btn-lg fw-bold text-light"href="{{route('stock_summery_report_godown_search_from')}}">Godown Wise Stock Summery</a>
                    <a class="btn-danger btn btn-lg fw-bold text-light" href="{{url('stock_summery_report_item_search_from')}}">Item Wise Stock Summery</a>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <form action="{{url('/stock_summery_report_item_search_from')}}" method="GET">
                <div class="card">
                    <div class="card-header bg-danger text-light">
                        <h4 class="card-title">Item Wise Stock Report</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-gorup row">
                            <div class="col-md-4 col-sm-12">
                                <label for="cono1" class="control-label col-form-label" >Item Name</label>
                                <select 
                                    class="form-control" name="item_id" id="item_id" required
                                    data-placeholder="Select a Item Name"
                                >
                                    <option value="" hidden>Select a Item Name</option>
                                    @php
                                        $items = App\Item::get();
                                    @endphp
                                    @foreach($items as $item)
                                        <option value="{{$item->id}}" {{ request()->item_id == $item->id ?"Selected": ' '  }}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="cono1" class="control-label col-form-label">From</label>
                                <input type="Date" class="form-control" name="fromDate" value="{{ request()->fromDate??' ' }}" required />
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="cono1" class="control-label col-form-label">To</label>
                                <input type="Date" class="form-control" name="toDate" value="{{ request()->toDate??' ' }}"required />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center" >
                        <button type="submit" class="btn btn-danger btn-lg text-light fw-bold" ><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
        </div>
		
        @if(request()->item_id)
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-danger text-light">
                    <h4 class="card-title">Item Wise Stock Report</h4>
                </div>
                <div class="card-body">
                    <table class="table" style="border: 1px solid #eee;text-align: center;" id="printArea">
                        <thead>
                             <tr>
                                <td colspan="8" style="text-align: center; border:0px !important;">
                                    @php
                                    $company = App\Companydetail::get();
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
                                    @endphp
        
                                    @foreach($company as $company_row)
        
                                    <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                                    <p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
                                        {{$company_row->mobile_number}}</p>
                                    @endforeach
                                    <h4> Item Wise Stock</h4>
                                    <h4> {{ $item->name??' ' }}</h4>
                                    <h5>From: {{ date('d-m-Y', strtotime(request()->fromDate)) }} - To:{{ date('d-m-Y', strtotime(request()->toDate)) }} </h5>
                                    <h5> Previous Stock : {{ number_format($prevousStockSumCount, 2)  }} </h5>
                                    @if ($prevousStockSumTotal > 0 && $prevousStockSumCount>0)
                                    <h5> Previous Average Price : {{ number_format($prevousStockSumTotal/$prevousStockSumCount, 2)  }} </h5>
                                    <h5> Previous Total Price : {{ number_format($prevousStockSumTotal, 2)  }} </h5>
                                    @endif
                                </td>
                            </tr>
                            <tr style="font-size:14px;font-weight: 800;">
                                <th style="padding: 5px 5px;width: 150px; text-align: center;">Date</th>
                                <th style="padding: 5px 5px;width: 100px;"> Stock Type</th>
                                <th style="padding: 5px 5px;width: 100px;"> Vch.No</th>
                                <th style="padding: 5px 5px;width: 100px;">Godown Id </th>
                                <th style="padding: 5px 5px;width: 100px;">In Qty</th>
                                <th style="padding: 5px 5px;width: 100px;"> Out Qty </th>
                                <th style="font-size:18px;padding: 5px 5px;width: 100px;"> Closing/Qty</th>
                                <th style="padding: 5px 5px;width: 100px;">Price </th>
                                <th style="padding: 5px 5px;width: 100px;">Total Price </th>
                            </tr>
                        </thead>
        
        
                        @php
                              $total_qty = 0;
                        @endphp
                        <tbody>
                            @foreach($item->stocks as $i => $item_row)
                            <tr style="font-size:14px;">
                                <td style="padding: 5px 5px;width: 100px;">{{ date('d-m-Y', strtotime($item_row->date)) }} </td>
                                <td style="padding: 5px 5px;width: 100px;">{{ str_replace('App\\', '',$item_row->stockable_type)??0  }} </td>
                                 <td style="padding: 5px 5px;width: 100px;">{{ $item_row->stockable->product_id_list ?? $item_row->stockable->vo_no ?? "N/A" }} </td>
                                <td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->godown)->name??'-'  }} </td>
                                <td style="padding: 5px 5px;width: 100px;">{{ $item_row->in_qty == 0?0 : number_format($item_row->in_qty, 2)  }} </td>
                                <td style="padding: 5px 5px;width: 100px;">{{ $item_row->out_qty == 0?0 : number_format($item_row->out_qty, 2)  }} </td>
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
        
                                <td style="font-size:18px;padding: 5px 5px;width: 100px;">{{ number_format($total_qty, 2)  }} </td>
                                <td style="padding: 5px 5px;width: 100px;">{{ number_format($item_row->average_price, 2)  }} </td>
                                @if ($item_row->total_average_price> 0)
        
                                <td style="padding: 5px 5px;width: 100px;">{{ number_format($item_row->total_average_price, 2)  }} </td>
                                @else
                                <td style="padding: 5px 5px;width: 100px;">{{ number_format($item_row->total_average_price*-1, 2)  }} </td>
        
                                @endif
                            </tr>
                            @endforeach
                        </tbody>        
                    </table>        
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-lg btn-danger text-light fw-bold "  onclick="printData()"><i class="fa fa-print"></i> Print</button> 
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>               
                    <a href="{{url()->full()}}&excel=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> Excel</a>               
                </div>
            </div>
        </div>
        @endif

	</div>
</div>

@endsection
@push('js')
<script>
    $(document).ready(function(){
       $('#item_id').select2();
    });
    
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


