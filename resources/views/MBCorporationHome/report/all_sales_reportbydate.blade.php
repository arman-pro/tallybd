@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">All Sales By Date</h3>
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
                <a class="btn-primary" href="{{route('all_sales_report')}}">All Sales</a>
                <a class="btn-success" href="{{route('item_wise_sales_report_search_form')}}">Item Wise Sales</a>
                <a class="btn-danger" href="{{route('party_wise_sales_report_search')}}">Party Wise Sales </a>
                <a class="btn-info" href="{{route('sale_man_wise_sales_report_search')}}">Sele Man Wise Sales </a>
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
        {{-- $purchases = App\SalesAddList::whereBetween('date',[$formDate,$toDate])->get(); --}}
        <div class="col-md-12" style="" id="">

            <table id="printArea" class="display" style="width:100%">
                <thead>
                    @php
                    $company = App\Companydetail::first();
                    $purchases = App\SalesAddList::whereBetween('date',[$formDate,$toDate])->get();

                    @endphp
                    <tr>
                        <th class='text-center' colspan="7" style=" border:0px !important;">
                            <h3>
                                {{$company->company_name}}
                            </h3>
                            <p>{{$company->company_address}}<br> {{$company->phone}},   Cell:  {{$company->mobile_number}}</p>
                            <h4>All Sales</h4>
                            <strong>From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong>
                        </th>
                    </tr>

                    <tr style="font-size:14px;font-weight: 800;">
                            <th style="width:7%">Date</th>
                            <th style="width:8%">Vch.No</th>
                            <th style="width:20%">Account Lager</th>
                            <th style="width:20%">Item Details</th>
                            <th style="width:5%;text-align: center;">Total Qty</th>
                            <th style="width:10%;text-align: center;">Price</th>
                            <th style="width:10%;text-align: center;">Total Price</th>
                        </tr>
                </thead>
                <tbody>
                     <?php
                        $total_price__ = 0;
                        $total_qty__ =0;
                    ?>
                    @foreach($purchases as $purchases_row)
                   
                    <tr style="font-size:14px;">
                        <td style="padding: 5px 5px;width: 100px;">{{ date('d-m-Y', strtotime($purchases_row->date)) }}
                        </td>
                        <td style="padding: 5px 5px;width: 50px;">
                            {{$purchases_row->product_id_list}}</td>
                        <td style="padding: 5px 5px;width: 50px;">
                            {{ optional($purchases_row->ledger)->account_name??'-'}}</td>
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
                               {{ optional($item_detais_row->item)->name??' '}}<br>
                               @endforeach
                        </td>



                        <td style="padding: 5px 5px;width: 150px;text-align: center;">
                            @foreach($item_detais as $item_detais_row)
                                {{ number_format($item_detais_row->qty, 2)??"0"}} <br>
                            @endforeach
                        </td>
                        <td style="padding: 5px 5px;width: 150px;text-align: center;">
                            @foreach($item_detais as $row)
                            {{ number_format($row->price, 2)??"0"}} <br>


                            @endforeach
                        </td>
                        <td style="padding: 5px 5px;width: 150px;text-align: center;">
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
                        <td colspan="7" style="text-align: right;font-weight:bold">{{ new_number_format($total_price__, 2) }} Tk </td>
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
