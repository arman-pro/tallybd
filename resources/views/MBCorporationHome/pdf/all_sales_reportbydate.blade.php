@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "All Sale Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;
        height: 11.7in; */
        margin: 0px;
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
        margin: 10px;
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
            <p style="margin:0;text-align:center;">{{$company->company_address}}<br>{{$company->phone}}, Call:
                {{$company->mobile_number}}</p>
            <h4 style="margin:0;text-align:center;">All Sales</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong></p>
            <table class="pdf-table">
                <thead>
                    @php                  
                        $purchases = App\SalesAddList::whereBetween('date',[$formDate,$toDate])->get();
                    @endphp
                    <tr style="font-size:14px;font-weight: 800;">
                        <th>Date</th>
                        <th>Vch.No</th>
                        <th>Account Ledger</th>
                        <th>Item Details</th>
                        <th style="text-align: center;">Total Qty</th>
                        <th style="text-align: center;">Price</th>
                        <th style="text-align: center;">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        $total_price__ = 0;
                        $total_qty__ =0;
                    ?>
                    @foreach($purchases as $purchases_row)
                   
                    <tr style="font-size:14px;">
                        <td style="padding: 5px 5px;">{{ date('d-m-Y', strtotime($purchases_row->date)) }}
                        </td>
                        <td style="padding: 5px 5px;">
                            {{$purchases_row->product_id_list}}</td>
                        <td style="padding: 5px 5px;">
                            {{ optional($purchases_row->ledger)->account_name??'-'}}</td>
                        <td style="padding: 5px 5px;text-align: left;">
                            @php
                                $qty=0;
                                $total_price = 0;
                                $subtotal_price= 0;
                                $item_detais=App\DemoProductAddOnVoucher::where("product_id_list", $purchases_row->product_id_list)
                                ->with('item')->get();

                                foreach ($item_detais as $item_detais_rowss) {
                                    $qty=$qty+$item_detais_rowss->qty;
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
                           <?php
                                $total_price__ += ($row->price * $row->qty) ?? 0;
                                $total_qty__ += ($row->qty) ?? 0;
                           ?>
                         {{new_number_format(($row->price * $row->qty) , 2)}}<br>
                                    @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                         <td colspan="5" style="text-align: right;font-weight:bold">{{  number_format($total_qty__, 2) }} </td>
                        <td colspan="2" style="text-align: right;font-weight:bold">{{ new_number_format($total_price__, 2) }} Tk </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection