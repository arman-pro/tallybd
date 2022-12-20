<?php

namespace App\Exports;

use App\AccountGroup;
use Illuminate\Http\Request;
use App\AccountLedger;
use App\Item;
use App\AccountLedgerTransaction;
use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelReport implements FromView
{
    public $request;
    public $method_name;
    
    public function __construct(Request $request, $method) 
    {
        $this->request = $request;
        $this->method_name = $method;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $method = $this->method_name;
        return $this->$method();
    }
    
    public function receivable_payable()
    {
        $request = $this->request;
        $ledger = [];
        $endMonth = null;
        if ($request->from_date && $request->to_date) {
            $startMonth = substr($request->from_date, strrpos($request->from_date, '-') + 1);
            $endMonth = $request->to_date .'-30';
            $last_day = date('d', strtotime('last day of this month', strtotime($request->to_date .'-01')));
            $endMonth = date('Y-m-d', strtotime($request->to_date .'-'.$last_day));
           
            $ledger = AccountLedger::whereHas('accountGroupName', function ($query) {
                    $query->where('account_group_nature', '!=', 'Income')
                        ->where('account_group_nature', '!=', 'Expenses');
                })->get();
     
        }
        return view('MBCorporationHome.excel.receivable_payable', compact('ledger', 'endMonth'));
    }
    
    public function all_recevie_paymentbydate()
    {
        $request = $this->request;
        $formdate = $request->form_date;
        $todate = $request->to_date;
        return view('MBCorporationHome.excel.all_recevie_paymentbydate', compact('formdate', 'todate'));
    }
    
    public function stock_summery_report_item_search_from()
    {
       $request = $this->request;
       return view('MBCorporationHome.excel.stock_summery_report_item');
    }
    
    public function stock_summery_report_godown_search_from()
    {
        $request = $this->request;
        return view('MBCorporationHome.excel.stock_summery_report_godwn');
    }
    
     public function stock_summery_report_category_search_from()
    {
        $request = $this->request;
        $category_name = $request->category_id;
        return view('MBCorporationHome.excel.stock_summery_report_catagory', compact('category_name'));
    }
    
    public function all_stock_summery_report()
    {
        return view('MBCorporationHome.excel.all_stock_summery_report');
    }
    
    public function party_wise_sales_report()
    {
        $request = $this->request;
        $account_ledger_id = $request->account_ledger_id;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        return view('MBCorporationHome.excel.party_wise_sales_report', compact('account_ledger_id', 'fromDate', 'toDate'));
    }
    
     public function item_wise_sales_report()
    {
        $request = $this->request;
        $item_id = $request->item_name;
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        $item = Item::whereId($item_id)->with(['demoProductAddOnVoucher' => function ($query) use ($formDate, $toDate) {
            $query->where('page_name', 'sales_addlist')->whereBetween('date', [$formDate, $toDate]);
        }])->first();
        return view('MBCorporationHome.excel.item_wise_sales_report', compact('item', 'formDate', 'toDate'));
    }
    
    public function all_sales_reportbydate()
    {
        $request = $this->request;
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        return view('MBCorporationHome.excel.all_sales_reportbydate', compact('formDate', 'toDate'));
    }
    
    public function party_wise_purchases_report()
    {
        $request = $this->request;
        $account_ledger_id = $request->account_ledger_id;
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        return view('MBCorporationHome.excel.party_wise_purchases_report', compact(
            'account_ledger_id',
            'formDate',
            'toDate'
        ));
    }
    
    public function item_wise_purchases_report()
    {
        $request = $this->request;
        $item_id = $request->item_name;
        $formDate = $request->form_date;
        $toDate = $request->to_date;

        $item = Item::whereId($item_id)->with(['demoProductAddOnVoucher' => function ($query) use ($formDate, $toDate) {
            $query->where('page_name', 'purchases_addlist')->whereBetween('date', [$formDate, $toDate]);
        }])->first();

        return view('MBCorporationHome.excel.item_wise_purchases_report', compact(
            'item_id',
            'item',
            'formDate',
            'toDate'
        ));
    }
    
    public function all_purchases_reportbydate()
    {
        $request = $this->request;
        $formDate = $request->form_date;
        $toDate = $request->to_date;
        return view('MBCorporationHome.excel.all_purchases_reportbydate', compact('formDate', 'toDate'));
    }
    
    public function account_ledger_group_reportbydate()
    {
        $request = $this->request;
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
        
        return view('MBCorporationHome.excel.account_ledger_group_report', compact(
            'formDate',
            'account_group_list',
            'toDate',
            'account_name',
            'groupAccount_ledger',
            'filter',
            'settingDate'
        ));
    }
    
    public function account_ledger_report_by_date()
    {
        $request = $this->request;
        $ledger_id  = $request->ledger_id;
        $ledger     = AccountLedger::whereId($ledger_id)->first();
        $formDate   = $request->form_date;
        $toDate     = $request->to_date;
        $account_tran = AccountLedgerTransaction::where('ledger_id', $ledger_id)->whereBetween(
            'date',
            [$formDate, $toDate]
        )->orderBy('date')->get()->unique('account_ledger__transaction_id');
        return view('MBCorporationHome.excel.account_ledger_report', compact('formDate', 'ledger', 'toDate', 'ledger_id', 'account_tran'));
    }
}
