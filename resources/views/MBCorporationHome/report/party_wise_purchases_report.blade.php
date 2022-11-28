@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Party Wise Purchases
    </h3>
    <div class="row">
        <style type="text/css">
            .topnav {
                overflow: hidden;
                background-color: #eee;
            }

            .topnav a {
                width: 33.33%;
                float: left;
                color: #000;
                text-align: center;
                padding: 5px 16px;
                text-decoration: none;
                font-size: 17px;
            }

            .topnav a:hover {
                background-color: #ddd;
                color: black;
            }

            .topnav a.active {
                background-color: #99A3A4;
                color: #fff;
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

        <div class="col-md-12" style="" id="">

            <table id="printArea" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th colspan="7" style="text-align: center; border:0px !important;">
                            @php
                            $company = App\Companydetail::get();
                            $account = App\AccountLedger::where('id',$account_ledger_id)->first();
                        // dd($account);
                            @endphp

                            @foreach($company as $company_row)

                            <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                            <p>{{$company_row->company_address}}<br> {{$company_row->phone}} Call:
                                {{$company_row->mobile_number}}</p>
                            @endforeach

                            <h4>Party Name : {{$account->account_name}}</h4>
                            <strong>From : {{date('d-m-Y', strtotime($formDate))}} TO : {{date('d-m-Y', strtotime($toDate))}} </strong>
                        </th>
                    </tr>

                    <tr style="font-size:14px;font-weight: 800;">
                        <th style="width:7%">Date</th>
                        <th style="width:8%">Vo.No</th>
                        <th style="width:20%">Account Lager</th>
                        <th style="width:20%">Item Details</th>
                        <th style="width:5%;text-align: center;">Total Qty</th>
                        <th style="width:10%;text-align: center;">Price</th>
                        <th style="width:10%;text-align: center;">Total Price</th>
                    </tr>
                </thead>

                @php

                $purchases = App\PurchasesAddList::where('account_ledger_id',$account_ledger_id)->
                        whereBetween('date', [$formDate, $toDate])->get();
                        $total_qty= 0;
                @endphp
                <tbody>
                    @foreach($purchases as $purchases_row)
                    <tr style="font-size:14px;">
                        <td >{{ date('d-m-Y', strtotime($purchases_row->date)) }}
                        </td>
                        <td>
                            {{$purchases_row->product_id_list}}</td>
                        <td>
                            {{ optional($purchases_row->ledger)->account_name??'-'}}</td>
                        <td style=" text-align: left;">
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

                        <td style="text-align: center;">
                            @foreach($item_detais as $item_detais_row)
                             <?php $total_qty += $item_detais_row->qty; ?>
                                {{ number_format($item_detais_row->qty, 2)??"0"}} <br>
                            @endforeach
                        </td>
                        <td style="text-align: center;">
                            @foreach($item_detais as $row)
                                    {{ number_format($row->price, 2)??"0"}} <br>
                            @endforeach
                        </td>
                        <td style="text-align: center;">@foreach($item_detais as $row)
                                        {{ number_format(($row->price * $row->qty) , 2)}}<br>
                                    @endforeach 
                        </td>

                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-center">Total</th>
                        <th style="text-align:right;">{{number_format($total_qty, 2) }} </th>
                        <td>&nbsp;</td>
                        <th class="text-center">{{number_format($purchases->sum('grand_total'), 2) }} Tk. </th>
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

