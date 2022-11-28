@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
		        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
       <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Dashboard , <a style="color:Red;font-size:30px; "<span>{{ date('l, d F, Y') }}</span> </a> <span>Time Now: {{ date('h: i: s  a') }}</span>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        
        
        @php
            $total_purchases =0;
            $settingDate = App\Companydetail::with('financial_year')->first()->financial_year;



            $purchases = App\PurchasesAddList::get();
            
            if(request()->has('from_date') && request()->has('to_date')) {
                $todaypurchases = App\PurchasesAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
            }else {
                $todaypurchases = App\PurchasesAddList::where('date', date('Y-m-d'))->get();
            }
            $purchases_return = App\PurchasesReturnAddList::get();
            
            if(request()->has('from_date') && request()->has('to_date')){
                $todaypurchases_return = App\PurchasesReturnAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
            }else {
                $todaypurchases_return = App\PurchasesReturnAddList::where('date', date('Y-m-d'))->get();
            }
            
            $total_SalesAddLists =App\SalesAddList::get();
            
            if(request()->has('from_date') && request()->has('to_date')) {
                $SalesAddList = App\SalesAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
           
            }else{
                $SalesAddList = App\SalesAddList::where('date', date('Y-m-d'))->get();
            }
            
            $total_return_SalesAddLists =App\SalesReturnAddList::get();
            
            if(request()->has('from_date') && request()->has('to_date')) {
                $SalesReturnAddList = App\SalesReturnAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
            }else {
                $SalesReturnAddList = App\SalesReturnAddList::where('date', date('Y-m-d'))->get();
            }
            
            $payment = App\Payment::get(['id', 'amount']);
            
            if(request()->has('from_date') && request()->has('to_date')) {
                $todayPayment = App\Payment::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get(['id', 'amount']);
            } else {
                $todayPayment = App\Payment::where('date', date('Y-m-d'))->get(['id', 'amount']);
            }
            $receive = App\Receive::get(['id', 'amount']);
            
            if(request()->has('from_date') && request()->has('to_date')) {
                $todayReceive = App\Receive::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get(['id', 'amount']);
            } else {
                $todayReceive = App\Receive::where('date', date('Y-m-d'))->get(['id', 'amount']);
            }
        
            // cash on hand -- daybook
            $account_group_list         =  App\AccountGroup::where('account_group_name', 'cash-in-hand')
                ->with('groupsUnder')
                ->with(['accountLedgers' => function($ledger) use ($settingDate){
                    $ledger->with('summary')->whereHas('summary',function($summary) use ($settingDate){
                        $summary->whereBetween('date',[$settingDate->financial_year_from, $settingDate->financial_year_to] );
                    } );
                }])
                ->first();
            $daybook= 0;
            foreach ($account_group_list->accountLedgers as $key => $ledger) {
                $daybook += optional($ledger->summary)->grand_total??0;
            }


            
                // Bank Book //
                $account_bank_list         =  App\AccountGroup::where('account_group_name', 'Bank Account')
                    ->with('groupsUnder')
                    ->with(['accountLedgers' => function($ledger) use ($settingDate){
                        $ledger->with('summary')->whereHas('summary',function($summary) use ($settingDate){
                            $summary->whereBetween('date',[$settingDate->financial_year_from, $settingDate->financial_year_to] );
                        } );
                    }])
                    ->first();
                $bankAccount= 0;
                foreach ($account_bank_list->accountLedgers as $key => $ledger) {
                    $bankAccount += optional($ledger->summary)->grand_total??0;
                }
            





        @endphp
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!--search form-->
            <form method="get" action="{{route('mb_cor_index')}}" id="form_search">
                <input type="hidden" name="from_date" class="form-control" id="from_date" required />
                <input type="hidden" name="to_date" class="form-control" id="to_date" required />
            </form>
            <!--style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"-->
            <div class="row">
                <div class="col-md-8 col-sm-12 px-3">&nbsp;</div>
                <div class="col-md-4 col-sm-12 px-3">
                    <div class="form-group">
                        <div id="reportrange" class="btn btn-primary" style="float:right;margin:auto;margin-bottom:10px;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!--end search form-->
          <!-- ============================================================== -->
          <!-- Sales Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- Column -->
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">
                 <span style="font-size:18px;font-weight:800;">Sale</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>
                 <span style="font-size:22px;">{{$SalesAddList->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;">Total  : {{$total_SalesAddLists->sum('grand_total')}}Tk</p>
                 
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">
                 <span style="font-size:18px;font-weight:800;">Sale Return</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>
                 <span style="font-size:22px;">{{$SalesReturnAddList->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;">Total  : {{$total_return_SalesAddLists->sum('grand_total')}}Tk</p>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Purchase</span>
                 <button class="btn btn-sm" style="background-color:#58D68D;;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{$todaypurchases->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;">Total  : {{$purchases->sum('grand_total')}}Tk</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Purchase Return </span>
                 <button class="btn btn-sm" style="background-color:#58D68D;;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{$todaypurchases_return->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;">Total  : {{$purchases_return->sum('grand_total')}}Tk</p>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Expense</span>
                 <button class="btn btn-sm" style="background-color:#7FB3D5;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{number_format($expense, 2)}} Tk</span>
                 <p style="font-size: 12px;">ALL Expense Account</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Income</span>
                 <button class="btn btn-sm" style="background-color:#7FB3D5;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{number_format($income, 2)}} Tk</span>
                 <p style="font-size: 12px;">ALL Income Account</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">



                 <span style="font-size:18px;font-weight:800;">Stock Value</span>

                 <br>
                 <br>

                 <span style="font-size:22px;">{{ number_format($stockValue, 2)}} </span>
                 <p style="font-size: 12px;">Total Stock Value</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Received</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{ $todayReceive->sum('amount')}} Tk</span>
                 <p style="font-size: 12px;">Total Received :{{ $receive->sum('amount')}}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Payment</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{ $todayPayment->sum('amount')}} Tk</span>
                 <p style="font-size: 12px;">Total Payment:{{ $payment->sum('amount')}}</p>
                </div>
              </div>
            </div>

           <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Bank Book</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{ number_format($bankAccount, 2) }} Tk</span>
                 <p style="font-size: 12px;">All Bank Balance </p>
                </div>
              </div>
            </div>
            <!-- Column -->
           <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Cash Book</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Day</button>
                 <br>
                 <br>

                 <span style="font-size:22px;">{{ number_format($daybook, 2) }}Tk</span>
                 <p style="font-size: 12px;">Clossing Cash-in-Hand </p>
                </div>
              </div>
            </div>
           <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;"> SMS Balance </span>
                 <br>
                 <br>

                 {{-- <span style="font-size:22px;"> Used : {{ $setting->total_sms??'0' }}/p</span> <br> --}}
                 {{-- <span style="font-size:22px;"> Unused :  {{ $setting->used_sms??'0' }}</span> --}}
                 <span style="font-size:22px;">   {{ $smsBalance??'0' }}</span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;">Profit & Loss</span>
                 <br>
                 <br>
                 
                   <span style="font-size:22px;">{{ number_format($getProfit['profit']??0, 2) }} (DR)</span>
                   <span style="font-size:22px;">- {{ number_format($getProfit['loss']??0, 2) }} (CR)</span>
                </div>
              </div>
            </div>
            <!-- Column -->
          </div>

          <div id="monthlySales" style="height: 300px; width: 100%;"></div>
          <!-- ============================================================== -->

          <!-- Recent comment and chats -->
          <!-- ============================================================== -->

          <!-- ============================================================== -->
          <!-- Recent comment and chats -->
          <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
@endsection

@push('js')

<script>

    $(document).ready(function(){
        $(function() {
            var home_url = "https://masumtraders.tallybd.xyz/";
            var searchParams = new URLSearchParams(window.location.search)
            var start = moment();
            var end = moment();
                
            if(searchParams.has('from_date')) {
                
                $('#reportrange span').html(searchParams.get('from_date').replaceAll('-', '/') + '-' + searchParams.get('to_date').replaceAll('-', '/'));
                 
            } else {
                
                $('#reportrange span').html('Filter By Date');
            }
        
            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                window.location.href = home_url+"?from_date="+ start.format('d-M-Y') + "&to_date=" + end.format('d-M-Y');
            }
        
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
            }, cb);
        
            
        
        });
    })

    window.onload = function () {


    // console.log( JSON.parse($mothlySeles.label));
    var chart = new CanvasJS.Chart("monthlySales", {
        animationEnabled: true,
        theme: "light2", // "light1", "light2", "dark1", "dark2"
        title:{
            text: "Monthly Sales"
        },
        axisY: {
            title: " "
        },
        data: [{
            type: "column",
            showInLegend: true,
            legendMarkerColor: "grey",
            legendText: "From : 1-01-22 To: 31-12-22",
            dataPoints: [
                // $.get("{{ route('mb_cor_index') }}", function(data){
                //     console.log(data);
                //     data.monthlyPurchaseReport.forEach(purchase => {

                //          '{ y:'+ purchase.y+', label:'+purchase.label+'}'
                //     })
                // }),

                { y: 266455,  label: "Jan" },
                { y: 169709,  label: "Feb" },
                { y: 158400,  label: "Mar" },
                { y: 142503,  label: "Apr" },
                { y: 101500, label: "May" },

            ]
        }]
    });
    chart.render();

    }
    </script>

@endpush
