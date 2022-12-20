<?php

namespace App\Exports;

use App\AccountLedgerTransaction;
use App\Companydetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DayBookExport implements FromView
{
    public $request; 

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $request = $this->request;
      
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
        
        return view('MBCorporationHome.excel.day_book_report', compact('formDate', 'toDate', 'transactions', 'users', 'company'));
    }
}
