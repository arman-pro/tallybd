@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Sale Man Wise Sale Report")

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
   
    <div class="p-0 content_area" >
        <div>
            <h3 style="font-weight: 800;margin:0;text-align:center;">{{$company->company_name}}</h3>
            <p style="margin:0;text-align:center;">{{$company->company_address}}<br> Call:
                {{$company->mobile_number}}</p>
            <h4 style="margin:0;text-align:center;">Sale Man Wise Report</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime($fromDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong></p>
            <table class="pdf-table">
                <thead>
                    <tr style="font-size:14px;font-weight: 800;">
                        <th >Date</th>
                        <th >Vch.No</th>
                        <th >Account Lager</th>
                        <th style="text-align: center;">Item Details</th>
                        <th style="text-align: center;">Total Qty</th>
                        <th style="text-align: center;">Price</th>
                        <th style="text-align: center;">Total Price</th>
                    </tr>
                </thead>
                @php
                $purchases = App\SalesAddList::where('sale_name_id',$sale_man_id)
                ->wherebetween('date', [$fromDate, $toDate])
                ->get();
                $total_qty = 0;
                @endphp
                <tbody>
                    @foreach($purchases as $purchases_row)
                <tr style="font-size:14px;">
                    <td style="padding: 5px 5px;">{{ date('d-m-Y',
                        strtotime($purchases_row->date)) }}
                    </td>
                    <td style="padding: 5px 5px;">
                        {{$purchases_row->product_id_list}}</td>
                    <td style="padding: 5px 5px;">
                        {{ optional($purchases_row->ledger)->account_name??'-'}}</td>
                    <td style="padding: 5px 5px;text-align: left;">
                        @php
                        
                        $total_price = 0;
                        $subtotal_price= 0;
                        $item_detais=App\DemoProductAddOnVoucher::where("product_id_list",
                        $purchases_row->product_id_list)
                        ->with('item')->get();

                        foreach ($item_detais as $item_detais_rowss) {
                        $total_qty += $item_detais_rowss->qty;
                        $subtotal_price=$subtotal_price+$item_detais_rowss->subtotal_on_product;
                        }
                        $total_price = $subtotal_price + $purchases_row->other_bill - $purchases_row->discount_total;

                        @endphp
                        @foreach($item_detais as $item_detais_row)
                        {{ optional($item_detais_row->item)->name??' '}}<br>
                        @endforeach
                    </td>
                    <td style="padding: 5px 5px;text-align: center;">
                        @foreach($item_detais as $item_detais_row)
                        {{ number_format($item_detais_row->qty, 2)??"0"}} <br>
                        @endforeach
                    </td>
                    <td style="padding: 5px 5px;text-align: center;">
                        @foreach($item_detais as $row)
                        {{ number_format($row->price, 2)??"0"}} <br>

                        @endforeach
                    </td>
                    <td style="padding: 5px 5px;text-align: center;">
                        @foreach($item_detais as $row)
                        {{ number_format(($row->price * $row->qty) , 2)}}<br>
                                    @endforeach
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-center">Total</th>
                        <th class="text-center">{{number_format($total_qty, 2)}}</th>
                        <td>&nbsp;</td>
                        <th style="text-align:right;">{{ number_format($purchases->sum('grand_total'), 2) }} Tk. </th>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>
</div>

@endsection