@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Item Wise Purchase Report")

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
        $dr = 0;
        $cr = 0;    
    ?>
   
    <div class="p-0 content_area" >
        <div>
            <h3 style="font-weight: 800;margin:0;text-align:center;">{{$company->company_name}}</h3>
            <p style="margin:0;text-align:center;">{{$company->company_address}}<br>{{$company->phone}}, Call:
                {{$company->mobile_number}}</p>
            <h4 style="margin:0;text-align:center;">All Purchase</h4>
            <p style="margin:0;text-align:center;"><strong>From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong></p>
            <table  class="pdf-table">
                <thead>         
                    <tr style="font-size:14px;font-weight: 800;">
                        <th >Date</th>
                        <th >Vch.No</th>
                        <th >Account Lager</th>
                        <th >Item Details</th>
                        <th >Total Qty</th>
                        <th style="text-align: center">Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                @php
                    $amount= 0;
                    $total_qty= 0;
                @endphp
                <tbody>
                     @foreach($item->demoProductAddOnVoucher??[] as $demo_row)
                    @php
                        $purchases = App\PurchasesAddList::where('product_id_list',$demo_row->product_id_list)->get();
                        $grand_total=0;
                        $amount += $demo_row->subtotal_on_product;

                    @endphp
                    @foreach($purchases as $purchases_row)
                    <tr style="font-size:14px;">
                        
                         <td >{{ date('d-m-y', strtotime($purchases_row->date)) }}
                        </td>
                        <td style="padding: 5px 5px;">{{$demo_row->product_id_list}}</td>
                        <td style="padding: 5px 5px;">
                            {{$purchases_row->ledger->account_name}}</td>
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
                            
                            @if($demo_row->item_id == $item_detais_row->item_id)
                                {{ optional($item_detais_row->item)->name??' '}}<br>
                            @endif
                            @endforeach
                            </td>
                        <td style="padding: 5px 5px;text-align: center;">
                             <?php $total_qty += $demo_row->qty; ?>
                            {{  $demo_row->qty }}
                        </td>


                        <td style="padding: 5px 5px;text-align: center;">
                            {{  number_format($demo_row->price, 2) }} 
                        </td>
                        <td style="padding: 5px 5px;text-align: Left;">
                            {{ number_format(($demo_row->qty ?? 0) * ($demo_row->price ?? 0), 2)}} 
                          
                        </td>
                    </tr>
                    @endforeach


                @endforeach
                </tbody>
                <tfoot>
                     <tr>
                        <th colspan="4" class="text-center">Total</th>
                        <td style="text-align: center;font-weight:bold">{{ number_format($total_qty, 2) }}  </td>
                        <td style="text-align: right;font-weight:bold">&nbsp;</td>
                        <td colspan="1" style="text-align: right;font-weight:bold">{{ number_format($amount, 2) }} Tk. </td>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>
</div>

@endsection