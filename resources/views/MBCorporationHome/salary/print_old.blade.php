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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        window.print();
    </script>
</head>

<body>

    <div style="background: #fff;padding: 2%;overflow-x: auto;" id="printTable">
        <table style="width: 100%">
            <tr>
                <td colspan="4" style="padding: 5px;">Vch NO. :<span
                        style="font-weight: 700;"> {{$salary->vo_no}}</span></td>
                <td colspan="1" style="padding: 5px 20px;text-align: right;">Date :<span
                        style="font-weight: 700;"> {{ date('d-m-y', strtotime($salary->date))}}</span></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;padding-top: 10px;">
                    @php
                    $company = App\Companydetail::get();
                    @endphp

                    @foreach($company as $company_row)

                    <h3 style="font-weight: 800;margin: 0;">{{$company_row->company_name}}</h3>
                    <p style="margin: 0;">{{$company_row->company_address}}</p>
                    <p style="margin: 0;"> Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
                    @endforeach
                    <span style="font-size: 18px;font-weight: 800;border-bottom: 4px solid #566573;">SALARY GENERATE</span>
                </td>
                @php
                $row = App\Companydetail::where('id','1')->first();
                @endphp
                <td style="width: 80px;">
                    <img src="{{asset($row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
                </td>
            </tr>
    
            <tr>
               
                <td colspan="2" style="font-size: 16px;">
                    <span style="font-weight: 800;">Department: </span>
                    <span style="font-weight: 700;padding-left: 50px;">{{$salary->department->name}}</span>
                </td>
                <td colspan="2" style="font-size: 16px;">
                    <span style="font-weight: 800;">Shift: </span>
                    <span style="font-weight: 700;padding-left: 50px;">{{$salary->shift->name}}</span>
                </td>
                <td colspan="2" style="font-size: 16px;">
                    <span style="font-weight: 800;">Degination: </span>
                    <span style="font-weight: 700;padding-left: 50px;">{{$salary->designation->name}}</span>
                </td>
            </tr>

            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
        </table>
        
        <table style="width: 100%">
            <thead>
                <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
                    <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Sl</th>
                    <th style="width: 30%;border-right: 1px solid #eee;padding: 5px;">Employee Name</th>
                    <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Working Day</th>
                    <th style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Salary</th>
                    <th style="width: 15%;border-right: 1px solid #eee;padding: 5px;">Salary Generate Date</th>
                </tr>
            </thead>
            <tbody>
                @empty($salary->details)
                    <tr>
                        <td colspan="5" class="text-center">
                            No Data Found
                        </td>
                    </tr>
                @endempty
                
                @if($salary->details->isNotEmpty())
                    @foreach($salary->details as $key => $salary_detail)
                    <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
                        <td style="width: 5%;border-right: 1px solid #eee;padding: 5px;">{{$key+1}}</td>
                        <td style="width: 30%;border-right: 1px solid #eee;padding: 5px;text-align: Left;">{{$salary_detail->employee->name}}</td>
                         <td style="width: 5%;border-right: 1px solid #eee;padding: 5px;">{{$salary_detail->day}}</td>
                        <td style="width: 10%;border-right: 1px solid #eee;padding: 5px;">{{ number_format($salary_detail->salary)}}</td>
                       
                        <td style="width: 15%;border-right: 1px solid #eee;padding: 5px;">{{ date('d-m-y', strtotime($salary_detail->salary_date))}}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                    <td colspan="2" style="width: 50px;border-right: 1px solid #eee;">Grand Total</td>
                    <td colspan="2"style="width: 10%;border-right: 1px solid #eee;">{{ number_format($salary->total_amount ?? 0)}}</td>
                    <td colspan='2'>&nbsp;</td>
                </tr>
                <tr style="border-top: 1px solid #eee;text-align: center;">
                    <td colspan="3" style="text-align: left;padding-left: 10px;">
                        Amount In Words :
                        <span style="font-size: 16px;font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($salary->total_amount ?? 0); @endphp Taka Only</span>
                    </td>
                    <td >
                        <br>
                        <p style=  "font-size: 16px;font-weight: 800;text-align: Left;">Manager </p>
                    <td colspan="2">
                        <br>
                        <p style="font-size: 16px;font-weight: 800;">for {{$row->company_name}}</p>
                        <br>
                        <p>Aurhorised Signatory</p>
                        <br>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>

<script src="{{asset('MBCorSourceFile')}}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('MBCorSourceFile')}}/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>

<!--<script src="{{asset('MBCorSourceFile')}}/dist/js/custom.min.js"></script>-->

</body>

</html>
