<?php

namespace App\Http\Controllers;

use App\AccountGroup;
use App\AccountLedger;
use App\Employee;
use App\Item;
use Illuminate\Http\Request;

class SearchLedgerController extends Controller
{
    public function activeLedger(Request $request)
    {
        return  response()->json(['ledgers' => AccountLedger::active()->searching('account_name', $request->name)->take(15)->get(['id', 'account_name']) ], 201);
    }

    public function activeGroup(Request $request)
    {
        return  response()->json(['groups' => AccountGroup::searching('account_group_name', $request->name)->take(15)->get(['id', 'account_group_name']) ], 201);
    }

    public function paymentLedger(Request $request)
    {
        return  response()->json(['ledgers' => AccountLedger::active()->active('payment')->searching('account_name', $request->name)->take(15)->get(['id', 'account_name']) ], 201);
    }
    public function expenseLedger(Request $request)
    {
    return  response()->json(['items' => AccountLedger::whereIn('account_group_id', [9,11])->searching('account_name', $request->name)->get(['id', 'account_name']) ], 201);
    }

    public function expenseLedger_old(Request $request)
    {

        $expensesLedger = AccountGroup::where('account_group_nature', 'Expenses')
        ->with(['accountLedgers'])
        ->get();
        $ledgers = [];
        $ledgerArray = [];
        $ledgerIdArray = [];
        foreach ($expensesLedger as $key => $ledger) {
            if(count($ledger->accountLedgers) > 0 ){
                for ($i=0; $i < count($ledger->accountLedgers); $i++) {
                    array_push( $ledgerIdArray,$ledger->accountLedgers[$i]->id);
                    array_push( $ledgerArray,$ledger->accountLedgers[$i]->account_name);
                }
            }
        }

        $ledgers = array_merge( $ledgerIdArray,$ledgerArray );
        // $ledgers = (array) $ledgers;
        return  response()->json(['items' =>$expensesLedger ], 201);
    }


    public function activeItem(Request $request)
    {
        return  response()->json(['items' => Item::active()->searching('name', $request->name)->take(15)->get(['id', 'name']) ], 201);

    }

    public function employee(Request $request)
    {
        return  response()->json(['items' => Employee::active()->searching('name', $request->name)->take(15)->get(['id', 'name']) ], 201);

    }
}
