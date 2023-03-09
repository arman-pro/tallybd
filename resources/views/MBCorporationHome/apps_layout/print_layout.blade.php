<?php
    // company details
    // $company_detail = App\Companydetail::where('id','1')->first();
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="Report Print" />
    <meta name="robots" content="noindex,nofollow" />
    <title>
        @hasSection ('title')
            @yield("title") - 
        @else
           <?php echo "Report" . "_" . date('d-m-y'); ?>
        @endif
        Accounting ERP Softwer
    </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset($company_detail->company_logo)}}" />
    <!-- Custom CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="{{asset('MBCorSourceFile')}}/print/report_print.css"  media="all" rel="stylesheet" />
    <style>
         @page {
            /* size: A4; */
            margin: 0;
        }
/*
        @media print {
            @page {
                size: A4;
            }
        } */
        
        .btn {
            padding: 10px 15px;
            border: none;
            color: white;
            background: #3d9932;
            display: block;
            text-decoration: none;
            width: 80px;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }
        
        .box {
            width: 100%;
            display: block;
            box-sizing: border-box;
            padding: 5px 10px;
        }
        
        @media print { 
            .box, .btn { display: none !important; } 
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="box">
        <a class="btn" href="{{url()->previous()}}">Back</a>
    </div>
    <div class="invoice">
        <div class="header-logo">
            <h3 class="margin-0" style="font-weight: 650; font-family:Calisto MT; font-size:30px;Color:Black"><b>{{$company_detail->company_name ?? "Company Title"}}</b></h3>
            <p class="margin-0">{{$company_detail->company_address ?? "Company Address"}}</p>
            <p class="margin-0">
                @if($company_detail->phone) 
                    Tell: {{$company_detail->phone}}
                @endif
                @if($company_detail->mobile_number)
                     Phone: {{$company_detail->mobile_number}}
                @endif
            </p>
            @if($company_detail->company_logo)
            <div class="logo">
                <img src="{{asset($company_detail->company_logo)}}" style="height: 80px; width: 80px;">
            </div>
            @endif
        </div>
        @yield('container')
    </div>
</body>
<script type="text/javascript">
    window.print();
</script>
@stack('js')
</html>
