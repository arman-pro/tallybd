<?php

namespace App\Http\Controllers\MBCorporation;

use App\Companydetail;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Sale_regester;
use App\Product;
use App\PurchasesAddList;
use App\SalesAddList;
use App\StockHistory;
use App\Traits\SMS;
use App\PurchasesReturnAddList;
use App\SalesReturnAddList;
use App\Payment;
use App\Receive;

use App\Http\Controllers\MBCorporation\ReportController;


class HomeController extends Controller
{

    use SMS;

    public function index(Request $request)
    {
    //  if($request->s) {
    //     dd(Helper::day_get_profit_loos([
    //         'from_date' => '2022-08-19',
    //         'to_date' => '2022-08-19',
    //     ]));
    //  }
        $setting = Companydetail::first();

        if($request->ajax()){
            if($request->has('sms')) {
                return response()->json((string)$this->checkBalance());
            }
            
            $monthlyPurchaseReport =PurchasesAddList::selectRaw('monthname(created_at) as label, sum(grand_total) as y')
            ->groupBy('label')
            ->get()
            ->toArray();

            $monthlySalesReport = SalesAddList::selectRaw('monthname(created_at) as month, sum(grand_total) as total_sale')
            ->groupBy('month')
            ->get();
            return response()->json(['monthlyPurchaseReport' => $monthlyPurchaseReport, 'monthlySalesReport' => $monthlySalesReport]);

        }
        
        if($request->has('from_date') && $request->has('to_date')) {
            $date = (object)[
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
            ];
            
            $expense = Helper::date_wise_head_account_summary('Expenses', $date);
            $income = Helper::date_wise_head_account_summary('Income', $date);
            $stockValue =$this->stockValue();
            $getProfit =  (new ReportController)->getProfitloss((array)$date);
           
        } else {
            $expense = Helper::headAccountSummary('Expenses')->sum('amount');
            $income = Helper::headAccountSummary('Income')->sum('amount');
            $stockValue =$this->stockValue();
            // $getProfit =  (new ReportController)->getProfitloss();
            $getProfit =  Helper::day_get_profit_loos([
                'from_date' => date('Y-m-d'),
                'to_date' => date('Y-m-d'),
            ]);
           
        }
        

        //$smsBalance=  0;  //$this->checkBalance();
        
        return view('MBCorporationHome.home', compact('setting',
        'expense','income','stockValue', 'getProfit'));
    }
    
    
    public function dashboard_request_ajax(Request $request) {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        
        $date = (object)[
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];
            
        $expense = Helper::date_wise_head_account_summary('Expenses', $date);
        $income = Helper::date_wise_head_account_summary('Income', $date);
        // $getProfit =   (new ReportController)->getProfitloss((array)$date);
        $getProfit = Helper::day_get_profit_loos([
            'from_date' => date('Y-m-d', strtotime($request->from_date)),
            'to_date' => date('Y-m-d', strtotime($request->to_date)),
        ]);
        
        $todaypurchases = PurchasesAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
                
        $todaypurchases_return = PurchasesReturnAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
                
        $SalesAddList = SalesAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
        
        $SalesReturnAddList = SalesReturnAddList::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get();
        
        $todayPayment = Payment::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get(['id', 'amount']);
        
        $todayReceive = Receive::whereDate('date', '>=', request()->from_date)
                ->whereDate('date', '<=', request()->to_date)
                ->get(['id', 'amount']);
                
        return response()->json([
            'toaday_sale' => $SalesAddList->sum('grand_total'),
            'today_sale_return' => $SalesReturnAddList->sum('grand_total'),
            'today_purchase' => $todaypurchases->sum('grand_total'),
            'today_purchase_return' => $todaypurchases_return->sum('grand_total'),
            'today_expense' => $expense,
            'today_income' => $income,
            'today_received' => $todayReceive->sum('amount'),
            'today_payment' => $todayPayment->sum('amount'),
            'profit_' => $getProfit['profit'],
            'loos_' => $getProfit['loos'],
        ]);
    }



    public function stockValue($date=null)
    {
        $all_total_pur_price = 0;

        foreach (Item::get(['id']) as $key => $item_row) {
           
                            
            $histories= StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList',
                        'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
                ->where('item_id', $item_row->id);
            if(!is_null($date) && isset($date['to_date']) && !is_null($date['to_date'])) {
                // $histories->whereDate('date', '>=', $date['from_date'])
                $histories->whereDate('date', '<=', $date['to_date']);
            }
            $histories = $histories->get();

                $totalCount = 0;
                $averagePrice = 0;
                $totalPrice = 0;
                $total_inqty = 0;
                $total_inqtyP = 0;
                foreach ($histories as $key => $history) {
                    if($history->in_qty >0){
                        $total_inqty += $history->in_qty;
                        $total_inqtyP += $history->total_average_price;
                    }
                    
                    $totalCount += $history->total_qty;
                    $totalPrice += $history->total_average_price;
                }
                
                if($total_inqtyP>0 && $total_inqty>0){
                    $averagePrice =  ($total_inqtyP / $total_inqty);
                }
                // else{
                //     $averagePrice =  $totalPrice==0?$averagePrice:($totalPrice / $totalCount);
                // }

                $all_total_pur_price += $averagePrice*$totalCount;
        }
        return $all_total_pur_price;

    }


     public function stockReport()
    {
        return view('MBCorporationHome.stockReport');
    }




    public function allitem()
    {
        return view('MBCorporationHome.Inventory.item');
    }


    public function allcatagory()
    {
        return view('MBCorporationHome.Inventory.catagory');
    }


    public function allbrand()
    {
        return view('MBCorporationHome.Inventory.brand');
    }

    public function add_product()
    {
        return view('MBCorporationHome.Inventory.add_product');
    }




    public function unitsManage()
    {
        return view('MBCorporationHome.Inventory.units');
    }

    public function taxesManage()
    {
        return view('MBCorporationHome.Inventory.taxes');
    }

    public function apply_taxeManage()
    {
        return view('MBCorporationHome.Inventory.apply_taxe');
    }

    public function stock_countManage()
    {
        return view('MBCorporationHome.Inventory.stock_count');
    }


    public function purchases_manage()
    {
        return view('MBCorporationHome.PurchasesInformation.purchases_manage');
    }
    public function purchases_oders()
    {
        return view('MBCorporationHome.PurchasesInformation.purchases_oders');
    }
    public function purchases_invoice()
    {
        return view('MBCorporationHome.PurchasesInformation.purchases_invoice');
    }
    public function purchases_return()
    {
        return view('MBCorporationHome.PurchasesInformation.purchases_return');
    }








    public function account_create()
    {
        return view('MBCorporationHome.MasterInformation.account_create');
    }
    public function account_group()
    {
        return view('MBCorporationHome.MasterInformation.account_group');
    }
    public function account_Ledger()
    {
        return view('MBCorporationHome.MasterInformation.account_Ledger');
    }
    public function promotions()
    {
        return view('MBCorporationHome.MasterInformation.promotions');
    }

    public function voucher_type()
    {
        return view('MBCorporationHome.MasterInformation.voucher_type');
    }
}
