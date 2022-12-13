<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="robots" content="noindex,nofollow" />
    <title>
        @hasSection ('title')
            @yield("title") - 
        @else
            Pdf Report -
        @endif
        Accounting ERP Softwer
    </title>
    <!-- Favicon icon -->
   
    <!-- Custom CSS -->
    <link href="{{asset('MBCorSourceFile')}}/dist/css/style.min.css" rel="stylesheet" />
    @stack('css')
</head>

<body>  
<div class="page-wrapper" style="background: none;">
    @yield('pdf_content')
</div>
</body>

</html>
