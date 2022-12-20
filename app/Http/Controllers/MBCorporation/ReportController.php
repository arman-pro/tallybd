<?php

namespace App\Http\Controllers\MBCorporation;

use App\AccountGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\Companydetail;
use App\Exports\DayBookExport;
use App\Exports\ExcelReport;
use App\Helpers\Helper;
use App\Item;
use App\StockHistory;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MBCorporation\HomeController;
use App\PurchasesAddList;
use App\Traits\SMS;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
use SMS;

    public function cashFlow(Request $request)
    {
        $startMonth = substr($request->from_date, strrpos($request->from_date, '-') + 1);
        $endMonth = substr($request->to_date, strrpos($request->to_date, '-') + 1);
        $account_ledger_list = [];
        if ($startMonth > 0 &&   $endMonth > 0) {
            $account_ledger_list = AccountGroup::where('account_group_name', 'cash-in-hand')
                ->with(['accountLedgers' => function ($ledger) use ($startMonth, $endMonth) {
                    $ledger
                        ->withCount([
                            'transitions as debit_sum' => function ($query) use ($startMonth, $endMonth) {
                                $query->whereMonth('date', '>=', $startMonth)
                                    ->whereMonth('date', '<=', $endMonth)
                                    ->select(DB::raw("SUM(debit)"));
                            }
                        ])

                        ->withCount([
                            'transitions as credit_sum' => function ($query) use ($startMonth, $endMonth) {
                                $query->whereMonth('date', '>=', $startMonth)
                                    ->whereMonth('date', '<=', $endMonth)
                                    ->select(DB::raw("SUM(credit)"));
                            }
                        ]);
                }])

                ->first()->accountLedgers;
        }
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.cashFlow', compact('account_ledger_list'));
            return $pdf->download('cash_flow_report_'.date('d_m_y_').substr(rand(), 0, 4).".pdf");
        }
        return view('MBCorporationHome.report.cashFlow', compact('account_ledger_list'));
    }
    
    public function receivable_payable(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'receivable_payable'), 'receiveable_payable_'.date('d_m_y').substr(rand(), 0, 4).'.xlsx');
        }
        $ledger = [];
        $endMonth = null;
        if ($request->from_date && $request->to_date) {
            $startMonth = substr($request->from_date, strrpos($request->from_date, '-') + 1);
            // $endMonth = substr($request->to_date, strrpos($request->to_date, '-') + 1);
            $endMonth = $request->to_date .'-30';
            // $ledger = AccountLedger::whereHas('accountGroupName', function ($query) {
            //         $query->where('account_group_nature', '!=', 'Income')
            //             ->where('account_group_nature', '!=', 'Expenses');
            //     })->withCount(['transitions as amount' => function ($query) use ($startMonth, $endMonth) {
            //         $query
            //             // ->whereMonth('date', '<=', $endMonth)
            //             ->where('date', '<=', $endMonth)
            //             ->select(DB::raw("SUM(debit) - SUM(credit)"));
            //     }])
            //     ->get();
            $last_day = date('d', strtotime('last day of this month', strtotime($request->to_date .'-01')));
            $endMonth = date('Y-m-d', strtotime($request->to_date .'-'.$last_day));
           
            $ledger = AccountLedger::whereHas('accountGroupName', function ($query) {
                    $query->where('account_group_nature', '!=', 'Income')
                        ->where('account_group_nature', '!=', 'Expenses');
                })->get();
     
        }
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.receivable_payable', compact('ledger', 'endMonth'));
            return $pdf->download('all_receive_payment_report_'.date('d_m_y_').substr(rand(), 0, 4).'.pdf');
        }
        return view('MBCorporationHome.report.receivable_payable', compact('ledger', 'endMonth'));
    }
    
    public function receivable_payablesms(Request $request)
    {
        $ledger = [];
        $endMonth = null;
        if ($request->from_date && $request->to_date) {
            $startMonth = substr($request->from_date, strrpos($request->from_date, '-') + 1);
            // $endMonth = substr($request->to_date, strrpos($request->to_date, '-') + 1);
            $endMonth = $request->to_date .'-30';
            // $ledger = AccountLedger::whereHas('accountGroupName', function ($query) {
            //         $query->where('account_group_nature', '!=', 'Income')
            //             ->where('account_group_nature', '!=', 'Expenses');
            //     })->withCount(['transitions as amount' => function ($query) use ($startMonth, $endMonth) {
            //         $query
            //             // ->whereMonth('date', '<=', $endMonth)
            //             ->where('date', '<=', $endMonth)
            //             ->select(DB::raw("SUM(debit) - SUM(credit)"));
            //     }])
            //     ->get();
            $last_day = date('d', strtotime('last day of this month', strtotime($request->to_date .'-01')));
            $endMonth = date('Y-m-d', strtotime($request->to_date .'-'.$last_day));
           
            $ledger = AccountLedger::whereHas('accountGroupName', function ($query) {
                    $query->where('account_group_nature', '!=', 'Income')
                        ->where('account_group_nature', '!=', 'Expenses');
                })->get();
     
        }

        return view('MBCorporationHome.report.receivable_payablesms', compact('ledger', 'endMonth'));
    }
    
    public function sms_send(Request $request)
    {
        
        if($request->ajax()) {
            $res = $this->sendSMS($request->phone, $request->message);
            return response()->json(['success' => true]);
        }
    }

    public function bankInterest()
    {
        $account_group_list = AccountGroup::get(['id', 'account_group_name']);
        $type = 'bankInterest';

        return view('MBCorporationHome.report.account_ledger_search_from', compact('account_group_list', 'type'));
    }

    public function balance_sheet_report(Request $request)
    {

        if($request->to_date) {
            $date = [
              'from_date' => $request->from_date,
              'to_date' => $request->to_date,
            ];
            
            // A + E = L + P + I
            $settingDate = Helper::activeYear();
            $financialDate = Companydetail::with('financial_year')->first()->financial_year;
            //$getProfit =  $this->getProfit($settingDate,  $financialDate);
            $getProfit =  $this->getProfitloss($date);
    
            $assets = Helper::headAccountSummary('Assets');
            $liabilities = Helper::headAccountSummary('Liabilities');
    
            $stockValue = (new HomeController)->stockValue($date);
            
            if($request->pdf) {
                $pdf = Pdf::loadView('MBCorporationHome.pdf.date_wise_balance_sheet_report', compact(
                    'assets',
                    'getProfit',
                    'stockValue',
                    'liabilities',
                    'settingDate'
                ));
                return $pdf->download("balance_sheeet_report".date("_d_m_y_").substr(rand(), 0, 3).".pdf");
            }
            return view('MBCorporationHome.report.date_wise_balance_sheet_report', compact(
                'assets',
                'getProfit',
                'stockValue',
                'liabilities',
                'settingDate'
            ));
        }
        
        
        // A + E = L + P + I
        $settingDate = Helper::activeYear();
        $financialDate = Companydetail::with('financial_year')->first()->financial_year;
        //$getProfit =  $this->getProfit($settingDate,  $financialDate);
        $getProfit =  $this->getProfitloss();


        $assets = Helper::headAccountSummary('Assets');
        $liabilities = Helper::headAccountSummary('Liabilities');

        $stockValue = (new HomeController)->stockValue();
        
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.balance_sheet_report', compact(
                'assets',
                'getProfit',
                'stockValue',
                'liabilities',
                'settingDate'
            ));
            return $pdf->download("balance_sheeet_report".date("_d_m_y_").substr(rand(), 0, 3).".pdf");
        }
        
        return view('MBCorporationHome.report.balance_sheet_report', compact(
            'assets',
            'getProfit',
            'stockValue',
            'liabilities',
            'settingDate'
        ));
    }


    public function bankCalculation(Request $request)
    {
        $ledger_id = $request->ledger_id;
        $ledger = AccountLedger::whereId($ledger_id)->first();
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        $percent = $request->percent;
        $account_tran = AccountLedgerTransaction::where('ledger_id', $ledger_id)->whereBetween(
            'date',[$formDate, $toDate]
        )
            ->select(DB::raw('sum(debit) - sum(credit) as amount'), DB::raw('date(date) as date'))
            ->orderBy('date')
            ->groupBy('date')
            ->get();
        $bankDays = $request->bankDays;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.bankCalculation', compact(
                'formDate',
                'ledger',
                'bankDays',
                'percent',
                'toDate',
                'ledger_id',
                'account_tran'
            ));
            return $pdf->download("bank_interese_report_".date("d_m_y")."_".substr(rand(), 0, 4).".pdf");
        }
        return view('MBCorporationHome.report.bankCalculation', compact(
            'formDate',
            'ledger',
            'bankDays',
            'percent',
            'toDate',
            'ledger_id',
            'account_tran'
        ));
    }

    public function getProfit($settingDate, $financialDate)
    {
        $loss = 0;
        $profit = 0;
        $opening = StockHistory::where('date', '<=', $financialDate->financial_year_from)->whereIn('stockable_type', [
            'App\Item', 'App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList', 'App\SalesReturnAddList', 'App\StockAdjustment',
            'App\WorkingOrder', 'App\Production'
        ])->get(['total_qty', 'total_average_price', 'date']);
        $present_stock = StockHistory::where('date', '<=', $financialDate->financial_year_to)->whereIn('stockable_type', [
            'App\Item', 'App\PurchasesReturnAddList', 'App\PurchasesAddList', 'App\SalesAddList', 'App\SalesReturnAddList', 'App\StockAdjustment',
            'App\WorkingOrder', 'App\Production'
        ])->get();


        $expenseGroup = Helper::headAccountSummary('Expenses');
        $incomeGroup = Helper::headAccountSummary('Income');
        $expenses = $income = 0;
        $income = $incomeGroup->sum('amount');
        $expenses = $expenseGroup->sum('amount');

        $totalPurchase = StockHistory::whereBetween('date', [$financialDate->financial_year_from, $financialDate->financial_year_to])->whereIn('stockable_type', ['App\PurchasesAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalReturnPurchase = StockHistory::whereBetween('date', [$financialDate->financial_year_from, $financialDate->financial_year_to])->whereIn('stockable_type', ['App\PurchasesReturnAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalSale = StockHistory::whereBetween('date', [$financialDate->financial_year_from, $financialDate->financial_year_to])->whereIn('stockable_type', ['App\SalesAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalReturnSale = StockHistory::whereBetween('date', [$financialDate->financial_year_from, $financialDate->financial_year_to])->whereIn('stockable_type', ['App\SalesReturnAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');

        $leftSide = 0;
        $rightSide = 0;
        if ($opening->sum('total_average_price') > 0 && $opening->sum('total_qty') > 0) {
            $leftSide = ($opening->sum('total_average_price') / $opening->sum('total_qty')) * $opening->sum('total_qty') + $totalPurchase + $totalReturnSale + $expenses;
        } else {
            $leftSide = $totalPurchase + $totalReturnSale + $expenses;
        }
        if ($present_stock->sum('total_average_price') > 0 && $present_stock->sum('total_qty') > 0) {
            $rightSide += ($present_stock->sum('total_average_price') / $present_stock->sum('total_qty')) * $present_stock->sum('total_qty');
            $rightSide += ($totalSale * -1);
            $rightSide += ($totalReturnPurchase * -1);
            $rightSide += ($income * -1);
        } else {
            $rightSide += ($totalSale * -1);
            $rightSide += ($totalReturnPurchase * -1);
            $rightSide += ($income * -1);
        }
        if ($leftSide > $rightSide) {
            $loss = $leftSide - $rightSide;
        } else {
            $profit = $rightSide - $leftSide;
        }
        return with(['loss' => $loss, 'profit' => $profit]);
    }

    public function day_book_report(Request $request)
    {
        if($request->excel){
            return Excel::download(new DayBookExport($request), 'daybook_'.date('d_m_y').substr(rand(), 0, 4).'.xlsx');
        }

        $users = User::all(['id', 'name']);
        $formDate   = $request->form_date ?? date('Y-m-d');
        $toDate     = $request->to_date ?? date('Y-m-d');
        $transactions = AccountLedgerTransaction::orderBy('date')->where('account_ledger__transaction_id', 'NOT LIKE', '%AL%');
        if ($formDate && $toDate) {
            $transactions = $transactions->whereBetween('date', [$formDate, $toDate]);
        }
        if ($request->type_name) {
            $transactions->where('account_ledger__transaction_id', 'LIKE', '%' . $request->type_name . '%');
        }
        if ($request->created_by) {
            $transactions = $transactions->Where('created_by', $request->created_by);
        }
        if (empty($formDate) && empty($toDate)) {
            $transactions = $transactions->Where('date', date('Y-m-d'));
        }
        $transactions = $transactions->get()->unique('account_ledger__transaction_id');
        $company = Companydetail::first();
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.day_book_report', compact('formDate', 'toDate', 'transactions', 'users', 'company'));
            return $pdf->download('day_book_'.date('d_m_y').'_'.substr(rand(), 5).'.pdf');
        }
        
        return view('MBCorporationHome.report.day_book_report', compact('formDate', 'toDate', 'transactions', 'users', 'company'));
    }

    public function day_book_reportbydate(Request $request)
    {
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        return view('MBCorporationHome.report.day_book_reportbydate', compact('formDate', 'toDate'));
    }

    public function account_ledger_search_from()
    {
        $account_group_list = AccountGroup::get(['id', 'account_group_name']);
        $type = null;
        return view('MBCorporationHome.report.account_ledger_search_from', compact('account_group_list', 'type'));
    }

    public function account_ledger_report_by_date(Request $request)
    {
        if($request->excel) {
             return Excel::download(new ExcelReport($request, 'account_ledger_report_by_date'), 'account_ledger_report_'.date('d_m_y').substr(rand(), 0, 4).'.xlsx');
        }
        $ledger_id  = $request->ledger_id;
        $ledger     = AccountLedger::whereId($ledger_id)->first();
        $formDate   = $request->form_date;
        $toDate     = $request->to_date;
        // $account_tran = AccountLedgerTransaction::where('ledger_id', $ledger_id)->whereBetween(
        //     'date',
        //     [$formDate, $toDate]
        // )->orderBy('date')->dd();
        $account_tran = AccountLedgerTransaction::where('ledger_id', $ledger_id)->whereBetween(
            'date',
            [$formDate, $toDate]
        )->orderBy('date')->get()->unique('account_ledger__transaction_id');
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.account_ledger_report', compact('formDate', 'ledger', 'toDate', 'ledger_id', 'account_tran'));
            return $pdf->download('account_ledger_'.date('d_m_y').'_'.substr(rand(), 5).'.pdf');
            // return $pdf->stream();
            // return view('MBCorporationHome.pdf.account_ledger_report', compact('formDate', 'ledger', 'toDate', 'ledger_id', 'account_tran'));
        }
        return view('MBCorporationHome.report.account_ledger_report', compact('formDate', 'ledger', 'toDate', 'ledger_id', 'account_tran'));
    }

    public function account_ledger_group_reportbydate(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'account_ledger_group_reportbydate'), 'account_ledger_group_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx');
        }
        $filter = $request->filter ?? 'all';
        $settingDate = Helper::activeYear();
        $account_name = $request->account_name;
        $account_group_list = AccountGroup::where('id', $account_name)->first();
        $groupAccount_ledger = AccountLedger::where('account_group_id', $account_group_list->id)
        ->with(['summary' => function($summary) use ($settingDate){
            $summary->where('financial_date', $settingDate);
        }])
        ->get();
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.account_ledger_group_report', compact(
                'formDate',
                'account_group_list',
                'toDate',
                'account_name',
                'groupAccount_ledger',
                'filter',
                'settingDate'
            ))->setOption([
                'isHtml5ParserEnabled' => true,
            ]);
            return $pdf->download('account_group_ledger_'.date('d_m_y_').substr(rand(), 0, 5).'.pdf');
        }
        
        return view('MBCorporationHome.report.account_ledger_group_report', compact(
            'formDate',
            'account_group_list',
            'toDate',
            'account_name',
            'groupAccount_ledger',
            'filter',
            'settingDate'
        ));
    }

    public function all_purchases_report()
    {
        return view('MBCorporationHome.report.all_purchases_report');
    }

    public function all_purchases_reportbydate(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'all_purchases_reportbydate'), 'all_purchase_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx');
        }
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.all_purchase_reportbydate', compact('formDate', 'toDate'));
            return $pdf->download('all_purchase_report_'.date('d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.all_purchases_reportbydate', compact('formDate', 'toDate'));
    }

    public function item_wise_purchases_report_search_form()
    {
        return view('MBCorporationHome.report.item_wise_purchases_report_search_form');
    }
    
    public function item_wise_purchases_report(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'item_wise_purchases_report'), 'item_wise_purchase_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx');
        }
        $item_id = $request->item_name;
        $formDate = $request->form_date;
        $toDate = $request->to_date;

        $item = Item::whereId($item_id)->with(['demoProductAddOnVoucher' => function ($query) use ($formDate, $toDate) {
            $query->where('page_name', 'purchases_addlist')->whereBetween('date', [$formDate, $toDate]);
        }])->first();

        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.item_wise_purchases_report', compact(
                'item_id',
                'item',
                'formDate',
                'toDate'
            ));
            return $pdf->download('item_wise_purchase_'.date('d_m_y').'.pdf');
        }

        return view('MBCorporationHome.report.item_wise_purchases_report', compact(
            'item_id',
            'item',
            'formDate',
            'toDate'
        ));
    }


    public function party_wise_purchases_report_search()
    {
        return view('MBCorporationHome.report.party_wise_purchases_report_search');
    }

    public function party_wise_purchases_report(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'party_wise_purchases_report'), 'party_wise_purchase_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'); 
        }
        $account_ledger_id = $request->account_ledger_id;
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.party_wise_purchases_report', compact(
                'account_ledger_id',
                'formDate',
                'toDate'
            ));
            return $pdf->download('party_wise_purchase'.date('_d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.party_wise_purchases_report', compact(
            'account_ledger_id',
            'formDate',
            'toDate'
        ));
    }

    public function all_sales_report()
    {
        return view('MBCorporationHome.report.all_sales_report');
    }

    public function all_sales_reportbydate(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'all_sales_reportbydate'), 'all_sale_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'); 
        }
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.all_sales_reportbydate', compact('formDate', 'toDate'));
            return $pdf->download('all_sale_report_'.date('d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.all_sales_reportbydate', compact('formDate', 'toDate'));
    }

    public function item_wise_sales_report_search_form()
    {
        return view('MBCorporationHome.report.item_wise_sales_report_search_form');
    }


    public function item_wise_sales_report(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'item_wise_sales_report'), 'item_wise_sale_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'); 
        }
        $item_id = $request->item_name;
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        $item = Item::whereId($item_id)->with(['demoProductAddOnVoucher' => function ($query) use ($formDate, $toDate) {
            $query->where('page_name', 'sales_addlist')->whereBetween('date', [$formDate, $toDate]);
        }])->first();
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.item_wise_sales_report', compact('item', 'formDate', 'toDate'));
            return $pdf->download('item_wise_sale_report_'.date('_d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.item_wise_sales_report', compact('item', 'formDate', 'toDate'));
    }

    public function party_wise_sales_report_search()
    {
        return view('MBCorporationHome.report.party_wise_sales_report_search');
    }
    public function sale_man_wise_sales_report_search()
    {
        return view('MBCorporationHome.report.sale_man_wise_sales_report_search');
    }

    public function party_wise_sales_report(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'party_wise_sales_report'), 'party_wise_sale_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'); 
        }
        $account_ledger_id = $request->account_ledger_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.party_wise_sales_report', compact('account_ledger_id', 'fromDate', 'toDate'));
            return $pdf->download('party_wise_sale_report_'.date('_d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.party_wise_sales_report', compact('account_ledger_id', 'fromDate', 'toDate'));
    }
    
    public function sale_man_wise_sales_report(Request $request)
    {
        $sale_man_id = $request->sale_man_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.sale_man_wise_sales_report', compact('sale_man_id', 'fromDate', 'toDate'));
            return $pdf->download('sale_man_wise_sale_'.date('_d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.sale_man_wise_sales_report', compact('sale_man_id', 'fromDate', 'toDate'));
    }

    public function all_stock_summery_report(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'all_stock_summery_report'), 'all_stock_summery_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx');  
        }
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.all_stock_summery_report');
            return $pdf->download('all_stock_summery'.date("_d_m_y").".pdf");
        }
        return view('MBCorporationHome.report.all_stock_summery_report');
    }
    
    public function all_stock_summery_report_by_date(Request $request)
    {

        return view('MBCorporationHome.report.all_stock_summery_by_date');
    }

    public function stock_summery_report_category_search_from(Request $request)
    {
        if($request->excel) {
            return Excel::download(new ExcelReport($request, 'stock_summery_report_category_search_from'), 'category_wise_stock_summery_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx');  
        }
        $category_name = $request->category_id;
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.stock_summery_report_catagory', compact('category_name'));
            return $pdf->download('category_stock_summery_'.date('d_m_y').'.pdf');
        }
        return view('MBCorporationHome.report.stock_summery_report_catagory_search_from', compact('category_name'));
    }

    public function stock_summery_reportbyCatagory(Request $request)
    {
        $catagory_name = $request->catagory_name;
        return view('MBCorporationHome.report.stock_summery_reportbyCatagory', compact('catagory_name'));
    }


    public function stock_summery_report_godown_search_from(Request $request)
    {
        if($request->excel) {
            return Excel::download(
                new ExcelReport($request, 'stock_summery_report_godown_search_from'), 
                'godown_wise_stock_summery_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'
            );  
        }
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.stock_summery_report_godwn');
            return $pdf->download('stock_suermmery_godwon_'.date('_d_m_y').".pdf");
        }
        return view('MBCorporationHome.report.stock_summery_report_godwn_search_from');
    }


    public function stock_summery_report_item_search_from(Request $request)
    {
        if($request->excel) {
            return Excel::download(
                new ExcelReport($request, 'stock_summery_report_item_search_from'), 
                'item_wise_stock_summery_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'
            );  
        }
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.stock_summery_report_item');
            return $pdf->download("item_stock_summery_report_".date("d_m_y").".pdf");
        }
        return view('MBCorporationHome.report.stock_summery_report_item_search_from');
    }


    public function all_recevie_payment()
    {
        return view('MBCorporationHome.report.all_recevie_payment');
    }
    public function all_recevie_paymentbydate(Request $request)
    {
        if($request->excel) {
            return Excel::download(
                new ExcelReport($request, 'all_recevie_paymentbydate'), 
                'all_receive_payment_'.date('d_m_y_').substr(rand(), 0, 4).'.xlsx'
            );  
        }
        $formdate = $request->form_date;
        $todate = $request->to_date;
        if($request->pdf){
            $pdf = Pdf::loadView('MBCorporationHome.pdf.all_recevie_paymentbydate', compact('formdate', 'todate'));
            return $pdf->download("receive_payment_report_".date("d_m_y").substr(rand(), 0, 5).'.pdf');
        }
        return view('MBCorporationHome.report.all_recevie_paymentbydate', compact('formdate', 'todate'));
    }

    public function profit_loss_search()
    {
        return view('MBCorporationHome.report.profit_loss_search');
    }
    public function profit_loss_bydate(Request $request, Helper $helper)
    {
        $settingDate = $helper::activeYear();
        $fromDate = $request->form_date;
        $toDate = $request->to_date;
        $opening = StockHistory::where('date', '<', $fromDate)->whereIn('stockable_type', ['App\Item', 'App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList', 'App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder', 'App\Production'])->get();
        $present_stock = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])->get();


        //$expenseGroup = Helper::headAccountSummary('Expenses');
        //$incomeGroup = Helper::headAccountSummary('Income');
        $expenses = $income = 0;
        //$income = $incomeGroup->sum('amount');
        //$expenses = $expenseGroup->sum('amount');
        
        //---------- Expenses-------------
        $expenseGroup = array();
        $TEamount = 0;
        $ExpenseGroup = DB::table('account_groups')->where('account_group_nature','Expenses')->get();
        foreach ($ExpenseGroup as $key => $expense) {
            $amount = 0;
            $Expenseledgers = DB::table('account_ledgers')->where('account_group_id',$expense->id)->get();
            
            foreach ($Expenseledgers as $key => $ledger) {
                $ledger_transactions = DB::table('account_ledger_transactions')->where('ledger_id',$ledger->id)->whereBetween('date', [$fromDate, $toDate])->get();
                foreach ($ledger_transactions as $key => $value) {
                    $amount += $value->debit-$value->credit;
                }
            }
            $expenseGroup[] = array('name' => $expense->account_group_name, 'amount' => $amount);
            $TEamount +=$amount;
        }
        
        $expenses = $TEamount;
        
        //---------- Income-------------
        $incomeGroup = array();
        $TIamount = 0;
        $IncomeGroup = DB::table('account_groups')->where('account_group_nature','Income')->get();
        foreach ($IncomeGroup as $key => $income) {
            $amount = 0;
            $Incomeledgers = DB::table('account_ledgers')->where('account_group_id',$income->id)->get();
            
            foreach ($Incomeledgers as $key => $ledger) {
                $ledger_transactions = DB::table('account_ledger_transactions')->where('ledger_id',$ledger->id)->whereBetween('date', [$fromDate, $toDate])->get();
                foreach ($ledger_transactions as $key => $value) {
                    $amount += $value->credit-$value->debit;
                }
            }
            $incomeGroup[] = array('name' => $income->account_group_name, 'amount' => $amount);
            $TIamount +=$amount;
        }
        
        $income = $TIamount;
        

        // $totalPurchase = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\PurchasesAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalPurchases = PurchasesAddList::whereBetween('date',[$fromDate,$toDate])->orderBy('date')->get();
        $totalPurchase = ($totalPurchases->sum('grand_total') ?? 0) - ($totalPurchases->sum('other_bill') ?? 0);
        $totalReturnPurchase = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\PurchasesReturnAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalSale = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\SalesAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalReturnSale = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\SalesReturnAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        
        if($request->pdf) {
            $pdf = Pdf::loadView('MBCorporationHome.pdf.profit_loss_by_date', compact(
                'fromDate',
                'toDate',
                'opening',
                'present_stock',
                'expenses',
                'totalPurchase',
                'totalReturnPurchase',
                'totalSale',
                'totalReturnSale',
                'income',
                'expenseGroup',
                'incomeGroup'
            ));
            return $pdf->download("profit_loos_report_".date('d_m_y').substr(rand(), 0, 4).".pdf");
        }

        return view('MBCorporationHome.report.profit_loss_by_date', compact(
            'fromDate',
            'toDate',
            'opening',
            'present_stock',
            'expenses',
            'totalPurchase',
            'totalReturnPurchase',
            'totalSale',
            'totalReturnSale',
            'income',
            'expenseGroup',
            'incomeGroup'
        ));
    }
    
    
    public function getProfitloss($date=null)
    {
       if(is_null($date)) {
            $fromDate = '2020-01-01';
            $toDate = date('Y-m-d');
       }else {
            if(!is_null($date) && isset($date['to_date']))
                $toDate = $date['to_date'];
            if(!is_null($date) && isset($date['from_date']))
                $fromDate = $date['from_date'];
       }
        $loss =0;
        $profit =0;
        // Opening
        $item = Item::get();
        $opening_total_pur_price = 0;
        foreach($item as $i=>$item_row){
            $opening= StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
            ->where('item_id', $item_row->id)
            // change by arman
            ->where('date', '<', $fromDate)->get();
            // ->where('date', '<', $fromDate)->get();
            $totalCount = 0;
            $total_in = 0;
            $averagePrice = 0;
            $totalPrice = 0;
            $totalPur_Price = 0;
            foreach ($opening as $key => $history) {
                $total_in += $history->in_qty;
                if($history->in_qty >0){
                    $totalPur_Price += $history->total_average_price;
                }
                $totalCount += $history->total_qty;
                $totalPrice += $history->total_average_price;
            }

            if($totalPur_Price > 0 && $total_in > 0){
                $averagePrice =  ($totalPur_Price / $total_in);
            }
            // else{
            //     $averagePrice =  $totalPrice==0?$averagePrice:($totalPrice / $totalCount);
            // }
            $opening_total_pur_price += $averagePrice*$totalCount;
        }

        //Present 
        $present_total_pur_price = 0;
        foreach($item as $i=>$item_row){
            $present_stock= StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
            ->where('item_id', $item_row->id)
            // change by arman
            ->where('date', '<=', $toDate)->get();
            // ->where('date', '<', $toDate)->get();
            $totalCount = 0;
            $total_in = 0;
            $averagePrice = 0;
            $totalPrice = 0;
            $totalPur_Price = 0;
            foreach ($present_stock as $key => $history) {
                $total_in += $history->in_qty;
                if($history->in_qty >0){
                    $totalPur_Price += $history->total_average_price;
                }
                $totalCount += $history->total_qty;
                $totalPrice += $history->total_average_price;
            }

            if($totalPur_Price>0 && $total_in>0){
                $averagePrice =  ($totalPur_Price / $total_in);
            }
            // else{
            //     $averagePrice =  $totalPrice==0?$averagePrice:($totalPrice / $totalCount);
            // }
            
            $present_total_pur_price += $averagePrice*$totalCount;
        }


        $expenses = $income = 0; 
        //---------- Expenses-------------
        $expenseGroup = array();
        $TEamount = 0;
        $ExpenseGroup = DB::table('account_groups')->where('account_group_nature','Expenses')->get();
        foreach ($ExpenseGroup as $key => $expense) {
            $amount = 0;
            $Expenseledgers = DB::table('account_ledgers')->where('account_group_id',$expense->id)->get();
            //dd($Expenseledgers);
            foreach ($Expenseledgers as $key => $ledger) {
                $ledger_transactions = DB::table('account_ledger_transactions')->where('ledger_id',$ledger->id)->whereBetween('date', [$fromDate, $toDate])->get();
                foreach ($ledger_transactions as $key => $value) {
                    $amount += $value->debit-$value->credit;
                }
            }
            $expenseGroup[] = array('name' => $expense->account_group_name, 'amount' => $amount);
            $TEamount +=$amount;
        }
        
        $expenses = $TEamount;
        
        //---------- Income-------------
        $incomeGroup = array();
        $TIamount = 0;
        $IncomeGroup = DB::table('account_groups')->where('account_group_nature','Income')->get();
        foreach ($IncomeGroup as $key => $income) {
            $amount = 0;
            $Incomeledgers = DB::table('account_ledgers')->where('account_group_id',$income->id)->get();
            //dd($Expenseledgers);
            foreach ($Incomeledgers as $key => $ledger) {
                $ledger_transactions = DB::table('account_ledger_transactions')->where('ledger_id',$ledger->id)->whereBetween('date', [$fromDate, $toDate])->get();
                foreach ($ledger_transactions as $key => $value) {
                    $amount += $value->credit-$value->debit;
                }
            }
            $incomeGroup[] = array('name' => $income->account_group_name, 'amount' => $amount);
            $TIamount +=$amount;
        }
        
        $income = $TIamount;

        $totalPurchase = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\PurchasesAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalReturnPurchase = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\PurchasesReturnAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalSale = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\SalesAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');
        $totalReturnSale = StockHistory::whereBetween('date', [$fromDate, $toDate])->whereIn('stockable_type', ['App\SalesReturnAddList'])->get(['total_qty', 'total_average_price'])->sum('total_average_price');

         
        $leftSide = 0;
        $rightSide= 0;
        if($opening_total_pur_price > 0){
            $leftSide = $opening_total_pur_price+$totalPurchase+$totalReturnSale+$expenses;
        }else{
            $leftSide = $totalPurchase+$totalReturnSale+$expenses;
        }
        if($present_total_pur_price >0){
            $rightSide += $present_total_pur_price;
            $rightSide+= abs($totalSale);
            $rightSide+= ($totalReturnPurchase*-1);
            $rightSide+= abs($income);

        }else{
            $rightSide+= abs($totalSale);
            $rightSide+= ($totalReturnPurchase*-1);
            $rightSide+= abs($income);
        }

        if($leftSide > $rightSide){
            $loss = $leftSide - $rightSide;
        }else{
            $profit = $rightSide - $leftSide;
        }
        return with(['loss' => $loss, 'profit' => $profit]);
    }
}
