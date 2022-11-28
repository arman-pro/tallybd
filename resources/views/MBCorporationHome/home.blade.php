@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
		        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
       <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Dashboard , <span style="font-size:30px;Color: Red;"id="time"></span></h4>
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
           
                ->first();
            $daybook= 0;
            foreach ($account_group_list->accountLedgers as $key => $ledger) {
                $daybook += optional($ledger->summary)->grand_total??0;
            }


            
                // Bank Book //
                $account_bank_list         =  App\AccountGroup::where('account_group_name', 'Bank Account')
                   
                    ->first();
                $bankAccount= 0;
                foreach ($account_bank_list->accountLedgers as $key => $ledger) {
                    $bankAccount += optional($ledger->summary)->grand_total??0;
                }
            



            $today_expense = App\Helpers\Helper::date_wise_head_account_summary('Expenses', (object)[
                'from_date' => date('Y-m-d'),
                'to_date' => date('Y-m-d'),
            ]);
            
            $today_income = App\Helpers\Helper::date_wise_head_account_summary('Income', (object)[
                'from_date' => date('Y-m-d'),
                'to_date' => date('Y-m-d'),
            ]);

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
              <div class="card card-hover"style="background-color:#499e25;border-radius: 5px;">
                <div class="box rounded">
                 <span style="font-size:22px;color:#fff;font-weight:800;">Sale</span>
                 <br>
                 <i class="fas fa-balance-scale-right"style="font-size:36px"></i>
                 <span style="font-size:22px;color:#fff;" id="toaday_sale">{{$SalesAddList->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">Total  : {{new_number_format($total_SalesAddLists->sum('grand_total'))}}Tk</p>
                 
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#db7082;border-radius: 5px;">
                <div class="box">
                 <span style="font-size:18px;color:#fff;font-weight:800;">Sale Return</span>
                 <br>
                  <i class="fas fa-balance-scale-left"style="font-size:36px"></i>
                 <span style="font-size:22px;color:#fff;" id="today_sale_return">{{$SalesReturnAddList->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">Total  : {{$total_return_SalesAddLists->sum('grand_total')}}Tk</p>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#33cb66;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Purchase</span>
                <br>
                 <i class="fas fa-balance-scale"style="font-size:36px;color:white"></i>

                 <span style="font-size:22px;color:#fff;" id="today_purchase">{{$todaypurchases->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">Total  : {{new_number_format($purchases->sum('grand_total'))}}Tk</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#db7082;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Purchase Return </span>
                 <br>
                 <i class="fas fa-balance-scale-left"style="font-size:36px;color:Green"></i>

                 <span style="font-size:22px;color:#fff;" id="today_purchase_return">{{$todaypurchases_return->sum('grand_total')}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">Total  : {{new_number_format($purchases_return->sum('grand_total'))}}Tk</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#0f4111;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Received</span>
                 <br>
                 <i class="fas fa-hand-holding-usd"style="font-size:36px;color:white"></i>

                 <span style="font-size:22px;color:#fff;" id="today_received">{{ $todayReceive->sum('amount')}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">Total Received :{{ new_number_format($receive->sum('amount'))}}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#f85549;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Payment</span>
                 <br>
                 <i class="fab fa-amazon-pay"style="font-size:36px;color:Green"></i>

                 <span style="font-size:22px;color:#fff;" id="today_payment">{{ $todayPayment->sum('amount')}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">Total Payment:{{new_number_format( $payment->sum('amount'))}}</p>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#d62c82;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Expense</span>
                 <br>
                <i class="fas fa-money-check"style="font-size:36px;color:#A52A2A"></i>

                 <span style="font-size:22px;color:#fff;" id="today_expense">{{number_format($today_expense ?? 0, 2)}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">ALL Expense: {{new_number_format($expense, 2)}}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#23c0e2;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;" >Income</span>
                 <br>
                 <i class="far fa-money-bill-alt"style="font-size:36px;color:Green"></i>

                 <span style="font-size:22px;color:#fff;"id="today_income">{{number_format($today_income ?? 0, 2)}} Tk</span>
                 <p style="font-size: 12px;color:#fff;">ALL Income: {{new_number_format($income, 2)}}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#2c6e44;border-radius: 5px;">
                <div class="box">



                 <span style="font-size:18px;color:#fff;font-weight:800;">Stock Value</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Today</button>
                 <br>
                 <i class="fa fa-refresh"style="font-size:36px"></i>

                 <span style="font-size:22px;color:#fff;" id="stock_value">{{ new_number_format($stockValue,)}}</span>
                 <p style="font-size: 12px;color:#fff;">Total Stock Value</p>
                </div>
              </div>
            </div>
            

           <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#2b5a86;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Bank Book</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Today</button>
                 <br>
                 <i class="fa fa-bank"style="font-size:30px;color:red"></i>

                 <span style="font-size:19px;color:#fff;" id="today_bank_book">{{ new_number_format($bankAccount, 2) }}Tk</span>
                 <p style="font-size: 12px;color:#fff;">All Bank Balance </p>
                </div>
              </div>
            </div>
            <!-- Column -->
           <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#db7082;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;color:#fff;font-weight:800;">Cash Book</span>
                 <button class="btn btn-sm" style="background-color:#3498DB;color:#fff;font-size: 12px;font-weight: 800; float: right;">Today</button>
                 <br>
                 <i class="fa fa-credit-card"style="font-size:36px;color:#006400"></i>

                 <span style="font-size:18px;color:#fff;">{{ new_number_format($daybook, 2) }}Tk</span>
                 <p style="font-size: 12px;color:#fff;">Clossing Cash-in-Hand </p>
                </div>
              </div>
            </div>
           
            <div class="col-md-6 col-lg-3 col-xlg-3">
              <div class="card card-hover"style="background-color:#10ccb3;border-radius: 5px;">
                <div class="box">

                 <span style="font-size:18px;font-weight:800;color:#fff;">Profit & Loss</span>
                 <br>
                 <br>
                 
                   <span style="font-size:22px;color:#fff;" id="profit_">{{ $getProfit['profit'] ?? 0.00 }} </span>(DR)<br/>
                   <span style="font-size:22px;color:#ea1515;" id="loos_">- {{ $getProfit['loos'] ?? 0.00 }} </span>(CR)
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
var timeDisplay = document.getElementById("time");


function refreshTime() {
  var dateString = new Date().toLocaleString("en-US", {timeZone: "Asia/Dhaka"});
  var formattedString = dateString.replace(", ", " - ");
  timeDisplay.innerHTML = formattedString;
}

setInterval(refreshTime, 1000);

    $(document).ready(function(){
        function getCb(data, status) {
          //  console.log(data);
            $('#sms_bal').text(data);
        }
        
        $.get('{{route('mb_cor_index')}}', {"sms": true}, getCb, 'json');
        
        $(function() {
            var home_url = "{{route('dashboard_ajax')}}";
            // var home_url = "https://masumtraders.tallybd.xyz/";
            // var searchParams = new URLSearchParams(window.location.search)
            var start = moment();
            var end = moment();
                
        
                
            $('#reportrange span').html('Filter By Date');
          
        
            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                // window.location.href = home_url+"?from_date="+ start.format('d-M-Y') + "&to_date=" + end.format('d-M-Y');
                var id_array = ["toaday_sale","today_sale_return",
                "today_purchase","today_purchase_return",
                "today_expense","today_income",
                "today_received","today_payment","profit_","loos_"];
                
                id_array.map(function(id){
                    $(`#${id}`).text('Loading...')
                });
                console.log(start.format('Y-M-D'), end.format('Y-M-D'));
                $.ajax({
                    url: `${home_url}?from_date=${start.format('Y-M-D')}&to_date=${end.format('Y-M-D')}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        id_array.map(function(id){
                            $(`#${id}`).text(`${res[id]} TK`);
                        });
                      
                    }
                });
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
