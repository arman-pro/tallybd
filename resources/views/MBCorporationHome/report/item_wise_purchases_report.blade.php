@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Item Wise Purchases
    </h3>
    <div class="row">
        <style type="text/css">
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
            table, td, th {
              border: 1px solid #000;
            }
            
            table { 
              border-collapse: collapse;
            }
        </style>
        <div class="col-md-12">
            <div class="topnav">
                <a class="btn-primary" href="{{route('all_purchases_report')}}">All Purchase</a>
                <a class="btn-success" href="{{route('item_wise_purchases_report_search_form')}}">Item Wise Purchase</a>
                <a class="btn-danger" href="{{route('party_wise_purchases_report_search')}}">Party Wise Purchase </a>
            </div>
        </div>

        <br>
        <br>
        <script lang='javascript'>
            function printData()
				{
				   var print_ = document.getElementById("main_table");
				   win = window.open("");
				   win.document.write(print_.outerHTML);
				   win.print();
				   win.close();
				}
        </script>
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <style type="text/css">
                .source_file_list {
                    height: 35px;
                    float: right;
                    background-color: #99A3A4;

                    padding: 5px;
                }

                .source_file_list a {
                    text-decoration: none;
                    padding: 5px 20px;
                    color: #fff;
                    font-size: 18px;

                }

                .source_file_list a:hover {
                    background-color: #D6DBDF;
                    color: #fff;
                }
            </style>
             
        </div>
        <div class="col-md-12" style="" id="">

            <table id="printArea" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th colspan="7" style="text-align: center; border:0px !important;">
                            @php
                            $company = App\Companydetail::get();
                            @endphp

                            @foreach($company as $company_row)

                            <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                            <p>{{$company_row->company_address}}<br>{{$company_row->phone}}, Call:
                                {{$company_row->mobile_number}}</p>
                            @endforeach
                            <h4>Item Wise Purchase</h4>
                            <strong>From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong>
                        </th>
                    </tr>

                    <tr style="font-size:14px;font-weight: 800;">
                        <th style="width:7%">Date</th>
                        <th style="width:8%">Vch.No</th>
                        <th style="width:20%">Account Lager</th>
                        <th style="width:16%">Item Details</th>
                        <th style="width:5%">Total Qty</th>
                        <th style="width:10%;text-align: center">Price</th>
                        <th style="width:10%">Total Price</th>
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
                        <td style="padding: 5px 5px;width: 100px;">{{$demo_row->product_id_list}}</td>
                        <td style="padding: 5px 5px;width: 100px;">
                            {{$purchases_row->ledger->account_name}}</td>
                            <td style="padding: 5px 5px;width: 150px; text-align: left;">
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
                        <td style="padding: 5px 5px;width: 50px; text-align: center;">
                             <?php $total_qty += $demo_row->qty; ?>
                            {{  $demo_row->qty }}
                        </td>


                        <td style="padding: 5px 5px;width: 150px;text-align: center;">
                            {{  number_format($demo_row->price, 2) }} 
                        </td>
                        <td style="padding: 5px 5px;width: 150px;text-align: Left;">
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
                        <td colspan="2" style="text-align: right;font-weight:bold">{{ number_format($amount, 2) }} Tk. </td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>
</div>

<div class="text-center">
    <button class="btn btn-lg btn-success "  onclick="printData()">Print</button>

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

