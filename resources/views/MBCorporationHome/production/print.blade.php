<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />



    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Matrix lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Matrix admin lite design, Matrix admin lite dashboard bootstrap 5 dashboard template" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Account & Inventory Management</title>
    <!-- Favicon icon -->
    @php
    $row = App\Companydetail::where('id','1')->first();
    @endphp
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset($row->company_logo)}}" />
    <!-- Custom CSS -->
    <link href="{{asset('MBCorSourceFile')}}/assets/libs/flot/css/float-chart.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{asset('MBCorSourceFile')}}/dist/css/style.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- js cdn -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        window.print();
    </script>
</head>

<body>


    <div style="background: #fff;padding: 2%;" id="printTable">
        <table style="width: 100%">
            <tr>
                <td colspan="6" style="padding: 5px;">Invoice NO. : <span
                        style="font-weight: 700;">{{$productionOrder->vo_no}}</span></td>
                <td colspan="3" style="padding: 5px 20px;text-align: right;">Date : <span
                        style="font-weight: 700;">{{date('m-d-y', strtotime($productionOrder->date))}}</span></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: center;padding-top: 10px;">
                    @php
                    $company_row = App\Companydetail::first();
                    @endphp

                    <h3 style="font-weight: 800;margin: 0;">{{$company_row->company_name}}</h3>
                    <p style="margin: 0;">{{$company_row->company_address}}</p>
                    <p style="margin: 0;"> Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>

                    <span style="font-size: 18px;font-weight: 800;border-bottom: 4px solid #566573;">INVOICE</span>
                </td>
                @php

                @endphp
                <td style="width: 80px;">
                    <img src="{{asset($company_row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
                </td>
            </tr>

        </table>
        <table style="width: 100%;border: 1px solid #b1acac;margin-bottom:2%">
            <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Sl</th>
                <th style="width: 30%;border-right: 1px solid #eee;padding: 5px;">Working Order Description</th>
                <th style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Qty</th>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</th>
                <th style="width: 15%;border-right: 1px solid #eee;padding: 5px;">Rate</th>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</th>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;"></th>
                <th style="width: 20%;">Amount</th>
            </tr>

            @php
            $i = 0;
            $total_qty = 0;
            $total_amount = 0;
            $DemoProductAddOnVoucher = App\DemoProductProduction::where('vo_no',$workingOrder->vo_no)->get();
            @endphp
            @foreach($DemoProductAddOnVoucher as $DemoProductAddOnVoucher_row)
            @php
            $i++;
            $total_qty = $total_qty + $DemoProductAddOnVoucher_row->qty ;
            $total_amount = $total_amount + ($DemoProductAddOnVoucher_row->price *
            $DemoProductAddOnVoucher_row->qty) ;

            $item = App\Item::where('id',$DemoProductAddOnVoucher_row->item_id)->first();
            @endphp
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;"> {{$i}}</td>
                <td style="width: 30%;border-right: 1px solid #eee;text-align: left;">
                    {{$item->name." ".$item->unit->name}}</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($DemoProductAddOnVoucher_row->qty,
                    2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 15%;border-right: 1px solid #eee;">{{$DemoProductAddOnVoucher_row->price}}
                </td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($DemoProductAddOnVoucher_row->price *
                    $DemoProductAddOnVoucher_row->qty , 2)}}</td>
            </tr>
            @endforeach
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;height: 150px;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;"></td>
                <td style="width: 10%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;"></td>
            </tr>

            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($total_qty, 2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($total_amount, 2)}}</td>
            </tr>
            <tr style="border-top: 2px solid #eee;text-align: center;">
                <th colspan="2" style="width: 50px;border-right: 1px solid #eee;">Title</th>
                <th colspan="2" style="width: 10%;border-right: 1px solid #eee;">Price</th>
                <th colspan="2" style="width: 20%;border-right: 1px solid #eee;">Qty</th>
                <th colspan="2" style="width: 20%;border-right: 1px solid #eee;">Total</th>
            </tr>
             @php
                $stotal = 0;
            @endphp
            @foreach($costinfo as $row )
            @php
            $stotal +=$row->total;
            @endphp
            <tr style="border-top: 2px solid #eee;text-align: center;">
                <td colspan="2" style="width: 50px;border-right: 1px solid #eee;">{{$row->title}}</td>
                <td colspan="2" style="width: 10%;border-right: 1px solid #eee;">{{$row->price}}</td>
                <td colspan="2" style="width: 20%;border-right: 1px solid #eee;">{{$row->qty}}</td>
                <td colspan="2" style="width: 20%;border-right: 1px solid #eee;">{{$row->total}}</td>
            </tr>
            @endforeach
                    
                <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                    <td style="width: 50px;border-right: 1px solid #eee;"></td>
                    <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                    <td style="width: 10%;border-right: 1px solid #eee;"> </td>
                    <td style="width: 5%;border-right: 1px solid #eee;"></td>
                    <td style="width: 15%;border-right: 1px solid #eee;"></td>
                    <td style="width: 5%;border-right: 1px solid #eee;"></td>
                    <td style="width: 5%;border-right: 1px solid #eee;"></td>
                    <td style="width: 20%;">{{ number_format($total_amount+$stotal, 2)}}</td>
                </tr>

                    <tr style="border-top: 1px solid #eee;text-align: center;">
                        <td colspan="5" style="text-align: left;padding-left: 10px;">
                            Amount In Words :<br>
                            <span style="font-size: 16px;font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($total_amount+$stotal); @endphp Taka Only</span>
                        </td>

                    </tr>



        </table>

        <table style="width: 100%;border: 1px solid #b1acac;" >


            <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Sl</th>
                <th style="width: 30%;border-right: 1px solid #eee;padding: 5px;">Production Order Description</>
                <th style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Qty</>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</>
                <th style="width: 15%;border-right: 1px solid #eee;padding: 5px;">Rate</>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;"></>
                <th style="width: 20%;">Amount</>
            </tr>

            @php
            $i = 0;
            $total_qty = 0;
            $total_amount = 0;
            $DemoProductAddOnVoucher = App\DemoProductProduction::where('vo_no',$productionOrder->vo_no)->get();
            @endphp
            @foreach($DemoProductAddOnVoucher as $DemoProductAddOnVoucher_row)
            @php
            $i++;
            $total_qty = $total_qty + $DemoProductAddOnVoucher_row->qty ;
            $total_amount = $total_amount + ($DemoProductAddOnVoucher_row->price *
            $DemoProductAddOnVoucher_row->qty) ;

            $item = App\Item::where('id',$DemoProductAddOnVoucher_row->item_id)->first();
            @endphp
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;"> {{$i}}</td>
                <td style="width: 30%;border-right: 1px solid #eee;text-align: left;">
                    {{$item->name." ".$item->unit->name}}</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($DemoProductAddOnVoucher_row->qty,
                    2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 15%;border-right: 1px solid #eee;">{{$DemoProductAddOnVoucher_row->price}}
                </td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($DemoProductAddOnVoucher_row->price *
                    $DemoProductAddOnVoucher_row->qty , 2)}}</td>
            </tr>
            @endforeach
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;height: 150px;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;"></td>
                <td style="width: 10%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;"></td>
            </tr>

            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($total_qty, 2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($total_amount, 2)}}</td>
            </tr>
            
             
            
                    <tr style="border-top: 1px solid #eee;text-align: center;">
                        <td colspan="5" style="text-align: left;padding-left: 10px;">
                            Amount In Words :<br>
                            <span style="font-size: 16px;font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($total_amount); @endphp Taka Only</span>
                        </td>
                        <td colspan="3">
                            <br>
                            <br>
                            <br>
                            <p style="font-size: 16px;font-weight: 800;">for {{$company_row->company_name}}</p>
                            <br>
                            <p>Aurhorised Signatory</p>
                            <br>
                </td>
            </tr>

        </table>
    </div>

</body>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/jquery/dist/jquery.min.js"></script>


</html>
