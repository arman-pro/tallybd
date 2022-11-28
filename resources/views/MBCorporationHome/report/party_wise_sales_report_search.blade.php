@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Party Wise Sales</h3>
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
                <a class="btn-primary" href="{{route('all_sales_report')}}">All Sales</a>
                <a class="btn-success" href="{{route('item_wise_sales_report_search_form')}}">Item Wise Sales</a>
                <a class="btn-danger" href="{{route('party_wise_sales_report_search')}}">Party Wise Sales </a>
                <a class="btn-info" href="{{route('sale_man_wise_sales_report_search')}}">Sele Man Wise Sales </a>
            </div>
        </div>
        <form action="{{url('/party_wise_sales_report')}}" method="POST">
            @csrf<div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cono1" class="control-label col-form-label">Party Name :</label>
                        <div>
                            <select class="form-control" name="account_ledger_id" id="account_ledger_id" required>
                                <option>Select</option>
                                @php
                                $AccountLedger = App\AccountLedger::get();
                                @endphp
                                @foreach($AccountLedger as $AccountLedger_row)
                                <option value="{{$AccountLedger_row->id}}">
                                    {{$AccountLedger_row->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="cono1" class="control-label col-form-label">From :</label>
                        <div>
                            <input type="Date" class="form-control" name="from_date" required="">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="cono1" class="control-label col-form-label">To :</label>
                        <div>
                            <input type="date" class="form-control" name="to_date" required="">
                        </div>
                    </div>
                </div>



                <div class="col-md-12" style="text-align: center;">
                    <br>
                    <button type="submit" class="btn btn-success"
                        style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
                </div>
            </div>
       {{-- </form>

        <br>
        <br>

        <div class="col-md-12" style="margin: 2%;" id="main_table">

            <br>
            <table class="table" id="printArea">
                <thead>
                    <tr>
                        <th colspan="7" style="text-align: center; border:0px !important;">
                            @php
                            $company = App\Companydetail::get();
                            @endphp

                            @foreach($company as $company_row)

                            <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                            <p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
                                {{$company_row->mobile_number}}</p>
                            @endforeach
                            <h4>Party Wise Sales</h4>
                        </th>
                    </tr>

                    <tr style="font-size:14px;font-weight: 800;">
                            <th style="width:7%">Date</th>
                            <th style="width:8%">Vch.No</th>
                            <th style="width:20%">Account Lager</th>
                            <th style="width:20%">Item Details</th>
                            <th style="width:5%">Total Qty</th>
                            <th style="width:10%">Price</th>
                            <th style="width:10%">Total Price</th>
                    </tr>
                </thead>
                @php
                $purchases = App\SalesAddList::get();
                @endphp
                <tbody>
                    @foreach($purchases as $purchases_row)
                    <tr style="font-size:14px;">
                        <td style="padding: 5px 5px;width: 100px;">{{ date('d-m-Y',
                            strtotime($purchases_row->date)) }}
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
                            $item_detais=App\DemoProductAddOnVoucher::where('page_name', 'sales_addlist')->where("product_id_list",
                            $purchases_row->product_id_list)
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

                        <td style="padding: 5px 5px;width: 150px;text-align: right;">
                            @foreach($item_detais as $item_detais_row)
                            {{ number_format($item_detais_row->qty, 2)??"0"}} <br>
                            @endforeach
                        </td>
                        <td style="padding: 5px 5px;width: 150px;text-align: right;">
                            @foreach($item_detais as $row)
                            {{ number_format($row->price, 2)??"0"}} <br>

                            @endforeach
                        </td>
                        <td style="padding: 5px 5px;width: 150px;text-align: center;">
                            {{ number_format($purchases_row->grand_total , 2)}} 
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="7" style="text-align: right;font-weight:bold">{{
                            number_format($purchases->sum('grand_total'), 2) }} </td>
                    </tr>
                </tfoot>



            </table>
        </div>

    </div>
</div> --}}

<br>
<div class="text-center">
    <button class="btn btn-lg btn-success "  onclick="printData()">Print</button>

</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){
        $('#account_ledger_id').select2();
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