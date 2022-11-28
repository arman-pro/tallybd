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
    <title>Channel</title>
    <!-- Favicon icon -->
    @php
    $row = App\Companydetail::where('id','1')->first();
    @endphp
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset($row->company_logo)}}" />
    <!-- Custom CSS -->
    <link href="{{asset('MBCorSourceFile')}}/assets/libs/flot/css/float-chart.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="{{asset('MBCorSourceFile')}}/dist/css/style.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- js cdn -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        window.print();
    </script>
</head>

<body>
    @php
    $purchases = App\SalesAddList::where('product_id_list',$product_id_list)->first();
    @endphp

    <div style="background: #fff;padding: 0%;" id="printTable">
        <table style="width: 100%">
            
            <tr>
                <td colspan="8" style="text-align: center;padding-top: 10px;">
                    @php
                    $company = App\Companydetail::get();
                    @endphp

                    @foreach($company as $company_row)

                    <h3 style="font-weight: 800;margin: 0;">{{$company_row->company_name}}</h3>
                    <p style="margin: 0;">{{$company_row->company_address}}</p>
                    <p style="margin: 0;"> Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
                    @endforeach
                    <span style="font-size: 18px;font-weight: 800;border-bottom: 4px solid #566573;">CHALAN</span>
                </td>
                @php
                $row = App\Companydetail::where('id','1')->first();
                @endphp
                <td style="width: 80px;">
                    <img src="{{asset($row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
                </td>
            </tr>@php
    $purchases = App\SalesAddList::where('product_id_list',$product_id_list)->first();
    @endphp
            <tr>
                @php
                $account = App\AccountLedger::where('id',$purchases->account_ledger_id)->first();
                @endphp
                <td colspan="2" style="padding: 20px;font-size: 16px;">
                    <span style="font-weight: 800;">Account:</span>
                    <span style="font-weight: 700;padding-left: 50px;">{{$account->account_name}}</span><br>
                    <span style="padding-left: 130px;">{{$account->account_ledger_address}}</span>
 </td>
              <td colspan="2" style="text-align: left;">
                <p>Date &nbsp;  &nbsp;: &nbsp;  &nbsp;{{$purchases->date}} <br>Chalan NO: {{$product_id_list}}<br> Delevery To:</p>
               
              </td>
                </td>
                <td colspan="2" style="padding: 10px;text-align: right;"> <span style="font-weight: 700;"></span></td>
            </tr>


            <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
                <td style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Sl</td>
                <td style="width: 60%;border-right: 1px solid #eee;padding: 5px;">Description</td>
                <td style="width: 20%;border-right: 1px solid #eee;padding: 5px;">Qty</td>
                <td style="width: 15%;border-right: 1px solid #eee;padding: 5px;">per</td>
               
            </tr>

            @php
            $i = 0;
            $total_qty = 0;
            $total_amount = 0;
            $DemoProductAddOnVoucher = App\DemoProductAddOnVoucher::where('product_id_list',$product_id_list)->get();
            // dd($DemoProductAddOnVoucher);
            @endphp
            @foreach($DemoProductAddOnVoucher as $row)
            @php
            $i++;
            $total_qty = $total_qty + $row->qty ;
            $total_amount = $total_amount + ($row->price *
            $row->qty) ;

            $item = App\Item::where('id',$row->item_id)->first();
            @endphp
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;"> {{$i}}</td>
                <td style="width: 30%;border-right: 1px solid #eee;text-align: left;">
                    {{$item->name." ".$item->unit->name}}</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ new_number_format($row->qty)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>

            </tr>
            @endforeach
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;height: 150px;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;"></td>
                <td style="width: 10%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
      
             
            </tr>

            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ new_number_format($total_qty)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
        

            </tr>
             @php
                $accountexpens = App\AccountLedger::where('id',$purchases->expense_ledger_id)->first();
                $gtotal = $purchases->other_bill+$total_amount;
            @endphp

            </tr>

                    <tr style="border-top: 1px solid #eee;text-align: center;">
                        <td colspan="5" style="text-align: left;padding-left: 10px;">
                             In Words :
                            <span style="font-size: 16px;font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($total_qty); @endphp  Only</span>
                        </td>
                        <td colspan="3">
                            <br>
                            <br>
                            <p style="font-size: 16px;font-weight: 800;">for {{$row->company_name}}</p>
                            <br>
                            <p>Aurhorised Signatory</p>
                            <br>
                        </td>
                    </tr>



        </table>
    </div>

</body>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('MBCorSourceFile')}}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="{{asset('MBCorSourceFile')}}/dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="{{asset('MBCorSourceFile')}}/dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="{{asset('MBCorSourceFile')}}/dist/js/custom.min.js"></script>
<!--This page JavaScript -->
<!-- <script src="../dist/js/pages/dashboards/dashboard1.js"></script> -->
<!-- Charts js Files -->
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot/excanvas.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot/jquery.flot.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot/jquery.flot.pie.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot/jquery.flot.time.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot/jquery.flot.stack.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot/jquery.flot.crosshair.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
<script src="{{asset('MBCorSourceFile')}}/dist/js/pages/chart/chart-page-init.js"></script>
</body>

</html>
