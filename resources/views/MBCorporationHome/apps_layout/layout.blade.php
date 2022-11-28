<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords"
        content="Accounting Software, ERP, Enterprise Resource Planing, Rice Mile Software, Inventory Software" />
    <meta name="robots" content="noindex,nofollow" />
    <title>
        @hasSection ('title')
            @yield("title") - 
        @else
            Dashboard -
        @endif
        Accounting ERP Softwer
    </title>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />   
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
    @stack('css')
    
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="{{route('mb_cor_index')}}">
                        <!-- Logo icon -->
                        <b class="logo-icon ps-2">

                            <img src="{{asset($row->company_logo)}}" alt="homepage" class="light-logo" width="25" />
                        </b>
                        <span class="logo-text ms-2">
                            <h6 style="padding-top: 10px;;">Business Management</h6>
                        </span>

                    </a>

                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                </div>

                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-start me-auto">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a>
                        </li>

                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box">
                            <a class="nav-link waves-effect waves-dark" href="javascript:void(0)">&nbsp;</a>
                            <form class="app-search position-absolute">
                            
                                <a class="srh-btn"><i class="mdi mdi-window-close"></i></a>
                            </form>
                            <a href="{{route('recevied_addlist_form')}}" class="btn btn-success" style="color:#fff; float:Center;">Received </a>
                            <a href="{{route('payment_addlist_form')}}" class="btn btn-warning" style="color:#fff; float:Center;">Payment </a>
                            <a href="{{route('purchases_addlist_from')}}" class="btn btn-info" style="color:#fff; float:Center;">Purchase </a>
                            <a href="{{route('sales_addlist_form')}}" class="btn btn-primary" style="color:#fff; float:Center;">Sales </a>
                        </li>
                    </ul>
                    <a href="{{route('day_book_report')}}" class="btn btn-secondary" style="color:#fff; float:Center;">Day Book </a>
                    <a href="{{route('account_ledger_search_from')}}" class="btn btn-success" style="color:#fff; float:Center;">Account Ledger </a>
                    <a href="{{route('all_stock_summery_report')}}" class="btn btn-info" style="color:#fff; float:Center;">Stock Report </a>

                            <html>
                            <body>


                            </html>
                        </span></a>
                    <!-- ============================================================== -->



                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('MBCorSourceFile')}}/assets/images/users/1.jpg" alt="user"
                                class="rounded-circle" width="31" />
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="javascript:void(0)"><i class="mdi mdi-account me-1 ms-1"></i>
                                {{ Auth::user()->name }}</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{url('/Admin-logout')}}"><i
                                    class="fa fa-power-off me-1 ms-1"></i> Logout</a>
                            <div class="dropdown-divider"></div>
                            <div class="ps-4 p-10">
                                
                            </div>
                        </ul>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <style type="text/css">
            .sidebar-item {
                font-size: 16px;

            }

            .sidebar-item li {
                font-size: 13px;
            }

            .sidebar-item li ul li {
                font-size: 12px;
            }
        </style>
        @php

        use App\linkpiority;
        use App\adminmainmenu;
        use App\adminsubmenu;

        $id = Auth::guard()->user();

        $mainlink = linkpiority::join('adminmainmenu', 'adminmainmenu.id', '=', 'linkpiority.mainlinkid')
        ->select('linkpiority.*','adminmainmenu.*')
        ->groupBy('linkpiority.mainlinkid')
        ->orderBy('adminmainmenu.serialNo', 'ASC')
        ->where('linkpiority.adminid',$id->id)
        ->get();


        $sublink = linkpiority::join('adminsubmenu', 'adminsubmenu.id', '=', 'linkpiority.sublinkid')
        ->select('linkpiority.*','adminsubmenu.*')
        ->orderBy('adminsubmenu.serialno', 'ASC')
        ->where('linkpiority.adminid',$id->id)
        ->get();


        $Adminminlink = adminmainmenu::orderBy('adminmainmenu.serialNo', 'ASC')
        ->get();

        $adminsublink = adminsubmenu::orderBy('adminsubmenu.serialno', 'ASC')

        ->get();

1111
        @endphp


        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="pt-4">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{route('mb_cor_index')}}" aria-expanded="false"><i
                                    class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a>
                        </li>

                        @if($id->id=="1")
                         

                        @php
                        $path = "http://" .$_SERVER['HTTP_HOST'].'/MainMenu';
                        $paths = "http://" .$_SERVER['HTTP_HOST'].'/SubMenu';
                        @endphp
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false">
                                <i class="mdi mdi-receipt"></i>
                                <span>Developer Tools</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{url('/MainMenu')}}" class="sidebar-link">Main
                                        Menu</a></li>
                                <li class="sidebar-item"><a href="{{url('/SubMenu')}}" class="sidebar-link">Sub Menu</a>
                                </li>

                            </ul>
                        </li>
                        @endif


                        @if($id->id=="1")


                        @if(count($Adminminlink) > 0)
                        @foreach($Adminminlink as $showMainlink)
                        @if($showMainlink->routeName == '0')
                        @php
                        $Mmenu = "http://" .$_SERVER['HTTP_HOST'].'/'.$showMainlink->Link_Name;
                        @endphp


                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-receipt"></i><span
                                    class="hide-menu">{{$showMainlink->Link_Name}}</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @if(count($adminsublink) > 0)
                                @foreach($adminsublink as $showSubLink)
                                @if($showSubLink->mainmenuId == $showMainlink->id)
                                @php
                                $Smenu = "http://".$_SERVER['HTTP_HOST'].'/'.$showSubLink->routeName;
                                @endphp
                                <li class="sidebar-item">
                                    <a href="{{URL::to('/')}}/{{$showSubLink->routeName}}" class="sidebar-link"><i
                                            class="fas fa-circle"></i><span
                                            class="hide-menu">{{$showSubLink->submenuname}}</span></a>
                                </li>
                                @endif
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        @else
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#"
                                aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span
                                    class="hide-menu">{{$showMainlink->Link_Name}}</span></a>
                        </li>
                        @endif
                        @endforeach
                        @endif


                        @else
                        @if(count($mainlink) > 0)
                        @foreach($mainlink as $showMainlink)
                        @if($showMainlink->routeName == '0')
                        @php
                        $Mmenu = "http://".$_SERVER['HTTP_HOST'].'/'.$showMainlink->Link_Name;
                        @endphp
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark  @if(Request::url() === $Mmenu){{'active'}}@else @endif"
                                href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-receipt"></i>


                                <span>{{$showMainlink->Link_Name}}</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @if(count($sublink) > 0)
                                @foreach($sublink as $showSubLink)
                                @if($showSubLink->mainmenuId == $showMainlink->id)
                                @php
                                $Smenu = "http://".$_SERVER['HTTP_HOST'].'/'.$showSubLink->routeName;
                                @endphp


                                <li class="sidebar-item @if(Request::url() === $Smenu){{'active'}}@else @endif">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="{{URL::to('/')}}/{{$showSubLink->routeName}}" aria-expanded="false"><i
                                            class="mdi mdi-view-dashboard"></i><span
                                            class="hide-menu">{{$showSubLink->submenuname}}</span></a>
                                </li>

                                @endif
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        @else
                        <li> <a href="#"><i class="icon icon-signal"></i> <span>{{$showMainlink->Link_Name}}</span></a>
                        </li>
                        @endif
                        @endforeach
                        @endif

                        @endif









                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            @if ($message = Session::get('warning'))
                <div class="alert alert-danger alert-block">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            
            @yield('admin_content')
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
          © <?= date('Y') ?> Ver:2.11 Developed by
            <a href="#">Morsalinngn</a>. User:{{ Auth::user()->name }}
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset('MBCorSourceFile')}}/assets/libs/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js"></script>
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
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>    
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable( {
                responsive: true,

            "lengthMenu": [[10, 5, 15, 25, 50, -1], [10,5,15, 25, 50, "All"]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print','pageLength'
                ]
            } );
        } );
    </script>

@stack('js')

</body>

</html>
