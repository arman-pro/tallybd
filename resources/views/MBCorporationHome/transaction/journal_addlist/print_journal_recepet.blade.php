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

    <div style="background: #fff;padding: 2%;" id="printTable">
        <table class="table" style="border: 1px solid #eee;">
            <tr>
                <td colspan="7" style="text-align: center;">
                    @php
                    $company = App\Companydetail::get();
                    @endphp

                    @foreach($company as $company_row)

                    <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                    <p>{{$company_row->company_address}}<br> Tel: {{$company_row->phone}}, Call:
                        {{$company_row->mobile_number}}</p>
                    @endforeach
                    <h4 style="font-size: 16px;">Journal VOUCHER</h4>
                </td>
                @php
                $row = App\Companydetail::where('id','1')->first();
                @endphp
                <td style="width: 80px;">
                    <img src="{{asset($row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
                </td>
            </tr>

            @php
            $payment = App\Journal::where('vo_no', $vo_no)->first();
            @endphp

            <tr style="border-bottom: 1px solid #eee;">
                <td colspan="6">
                    <p>Voucher No. &nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp;{{$payment->vo_no}}</p>

                </td>
                <td colspan="2" style="text-align: left;">
                    <p>Date &nbsp; &nbsp;: &nbsp; &nbsp; {{ date('d-m-Y', strtotime($payment->date)) }}</p>
                </td>
            </tr>

            <tr style="font-size:14px;font-weight: 800;border-bottom: 1px solid #fff;">
                <td style="padding: 5px 5px;width: 350px;" colspan="3"><span
                        style="border-bottom: 1px solid #000;">Particulars</span></td>
                <td style="padding: 5px 5px; text-align: left;"><span style="border-bottom: 1px solid #000;">DEBIT
                        (TK.)</span></td>
                <td style="padding: 5px 5px; text-align: left;"><span style="border-bottom: 1px solid #000;">CRRDIT
                        (TK.)</span></td>
                <td style="padding: 5px 5px;text-align: left;"><span
                        style="border-bottom: 1px solid #000;">Narration</span></td>
                <td colspan="2"></td>
            </tr>
            @php
            $total_dr = 0;
            $contra_drcr = App\DemoContraJournalAddlist::where('vo_no',$vo_no)->get();
            foreach($contra_drcr as $contra_row){
                if($contra_row->drcr == 'Dr'){
                    $total_dr=$total_dr + $contra_row->amount ;
                }
                }
                @endphp
                @foreach($contra_drcr as $contra_drcr_row)

                <tr style="border-bottom: 1px solid #fff;">
                    <td style="padding: 5px 5px;width: 350px;" colspan="3">

                         {{$contra_drcr_row->ledger->account_name}}
                    </td>
                    @if($contra_drcr_row->drcr == 'Dr') 
                        <td style="padding: 5px 5px; text-align: left;">
                            {{$contra_drcr_row->amount}}.00
                        </td>
                        <td style="padding: 5px 5px; text-align: left;"></td>
                        @elseif($contra_drcr_row->drcr  == 'Cr')
                        <td style="padding: 5px 5px; text-align: left;"></td>
                        <td style="padding: 5px 5px; text-align: left;">{{$contra_drcr_row->amount}}.00</td>
                        @endif
                        <td style="padding: 5px 5px;text-align: left;">{{$contra_drcr_row->note}}</td>
                        <td colspan="2"></td>
                </tr>
                @endforeach
                <tr style="height: 150px;"></tr>

                <tr>
                    <td style="padding: 5px 5px;width: 350px;" colspan="3"></td>
                    <td style="padding: 5px 5px; text-align: left;border: 1px solid #eee;">{{$total_dr}}.00</td>
                    <td colspan="4" style="padding: 5px 5px; text-align: left;border: 1px solid #eee;">{{$total_dr}}.00
                    </td>
                </tr>
                 
                        <tr>
                            <td style="padding: 5px 5px;width: 350px;" colspan="5">
                                <p style="font-weight: 800;">>@php echo App\Helpers\Helper::NoToWord($total_dr); @endphp Taka Only</p>
                                <p>Printed on
                                    @php
                                    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                                    echo $dt->format('j-m-Y , g:i a');
                                    @endphp
                                </p>
                            </td>
                            <td style="font-weight: 800;text-align: left;"> <br><br> Receiver By</td>
                            <td style="font-weight: 800;text-align: left;"> <br><br> Verified By</td>
                            <td style="font-weight: 800;text-align: left;"> <br><br> Authorised By</td>
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
