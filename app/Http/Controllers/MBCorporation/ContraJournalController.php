<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\DemoContraJournalAddlist;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Journal;
use App\LedgerSummary;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;
use Session;
use Datatables;

class ContraJournalController extends Controller
{
    //add contra_addlist .....................
    //add contra_addlist .....................
    
    public function contra_addlist(Request $request)
    {
        $Journal = Journal::where("page_name", 'contra')->orderBy('date', 'desc')->paginate(10);
        return view('MBCorporationHome.transaction.contra_addlist.index', compact('Journal'));
    }
    public function contra_addlist_form()
    {
        return view('MBCorporationHome.transaction.contra_addlist.contra_addlist_form');
    }

    public function delete_contra_addlist($id)
    {

        try {
            DB::beginTransaction();

            $contra = Journal::where("id", $id)->with('demoDetails', 'transaction')->first();

            foreach ($contra->demoDetails as $key => $details) {

                $summary = LedgerSummary::where('ledger_id', $details->ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                // dd($summary);
                if ($summary) {
                    if ($details->drcr == 'Dr') {
                        $summary->update(['debit' =>  abs($summary->debit - $details->amount) ]);

                        $this->deleteTransaction(
                            $contra,
                            'debit',
                            $contra->date,
                            $details->vo_no,
                            $details->ledger_id,
                            $details->amount
                        );
                    } else {
                        $summary->update(['credit' => abs($summary->credit - $details->amount) ]);
                        $this->deleteTransaction(
                            $contra,
                            'credit',
                            $contra->date,
                            $details->vo_no,
                            $details->ledger_id,
                            $details->amount
                        );
                    }
                } else {
                    dd('something is wrong');
                }
                $details->delete();
                // dd(,$summary);
            }
            $contra->delete();
            (new LogActivity)->addToLog('Contra Deleted.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->to('contra_addlist');
    }

    public function edit_contra_addlist($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        $mes = "";
        $contra = Journal::where("id", $id)->get();
        return view('MBCorporationHome.transaction.contra_addlist.edit_contra_addlist', compact('contra', 'mes'));
    }

    public function Update_Contra_addlist(Request $request,Helper $helper, $id)
    {
        $request->validate([
            'vo_no' => 'required',
            'page_name' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,

        ]);

        $contra = Journal::where("id", $id)->with('demoDetails', 'transaction')->first();

        try {
            DB::beginTransaction();


            $type_id = 'contra_id';

            if (empty($request->account_id)) {
                foreach ($contra->demoDetails as $key => $details) {

                    $summary = LedgerSummary::where('ledger_id', $details->ledger_id)
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();

                    if ($details->drcr == 'Dr') {
                        $summary->update(['debit' =>  abs($summary->debit - $details->amount)]);
                        $this->deleteTransaction(
                            $contra,
                            'debit',
                            $request->date,
                            $details->vo_no,
                            $details->ledger_id,
                            $details->amount
                        );
                    } else {
                        $summary->update(['credit' =>  abs($summary->credit - $details->amount) ]);
                        $this->deleteTransaction(
                            $contra,
                            'credit',
                            $request->date,
                            $details->vo_no,
                            $details->ledger_id,
                            $details->amount
                        );
                    }
                    $details->delete();

                }
            }
            if ($request->account_id) {
                // dd($request->all());
                $oldLedgerId = [];
                foreach ($contra->demoDetails as $key => $details) {
                    array_push($oldLedgerId, $details['ledger_id']);
                }
                $deletedLedger = array_diff($oldLedgerId, $request->account_id);

                if (count($deletedLedger) > 0) {

                    foreach ($deletedLedger as $key => $value) {

                        $summary = LedgerSummary::where('ledger_id', $value)
                        ->where('financial_date', (new Helper)::activeYear())
                        ->first();
                        $details = $contra->demoDetails()->where('ledger_id', $value)->first();
                        // dd($details);
                        if ($summary) {
                            if ($details->drcr == 'Dr') {
                                $summary->update(['debit' =>  abs($summary->debit - $details->amount)]);
                                $this->deleteTransaction(
                                    $contra,
                                    'debit',
                                    $request->date,
                                    $details->vo_no,
                                    $details->ledger_id,
                                    $details->amount
                                );
                            } else {
                                $summary->update(['credit' => abs($summary->credit - $details->amount)]);
                                $this->deleteTransaction(
                                    $contra,
                                    'credit',
                                    $request->date,
                                    $details->vo_no,
                                    $details->ledger_id,
                                    $details->amount
                                );
                            }
                            $details->delete();
                        }
                    }
                }
            }


            if (!empty($request->new_account_id)) {

                foreach ($request->new_account_id as $key => $value) {

                    $id_row = rand(111111, 999999) . '-' . date('y');

                    $row = DemoContraJournalAddlist::create([
                        'id_row' => $id_row,
                        $type_id => $contra->id,
                        'ledger_id' => $value,
                        'vo_no' => $request->vo_no,
                        'page_name' => $request->page_name,
                        'account_name' => $value,
                        'drcr' => $request->new_drcr_text[$key],
                        'amount' => $request->new_amount[$key],
                        'note' => $request->new_note[$key],
                    ]);
                    $summary = LedgerSummary::where('ledger_id', $value)
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();

                    if ($summary) {
                        if ($row->drcr == 'Dr') {
                            $summary->update(['debit' => $row->amount + $summary->debit]);
                            $this->transaction(
                                $contra,
                                'debit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        } else {
                            $summary->update(['credit' => $row->amount + $summary->credit]);
                            $this->transaction(
                                $contra,
                                'credit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        }
                    } else {
                        if ($row->drcr == "Dr") {
                            LedgerSummary::updateOrCreate(['ledger_id' => $row->ledger_id,'financial_date' => (new Helper)::activeYear()], [
                                'debit' => $row->amount
                            ]);

                            $this->transaction(
                                $contra,
                                'debit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        } else {
                            LedgerSummary::updateOrCreate(['ledger_id' => $row->ledger_id, 'financial_date' => (new Helper)::activeYear()], [
                                'credit' => $row->amount
                            ]);

                            $this->transaction(
                                $contra,
                                'credit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        }
                    }
                }
            }
            (new LogActivity)->addToLog($request->page_name . ' Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage(), $ex->getLine());
        }
        if($request->print){
            return $this->print_contra_recepet($id);
        }
        return back();
    }
    public function Update_Journal_addlist(Request $request,Helper $helper, $id)
    {
        $request->validate([
            'vo_no' => 'required',
            'page_name' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,

        ]);

        $contra = Journal::where("id", $id)->with('joDemoDetails', 'transaction')->first();
        $contra->update(['date' => $request->date]);
        AccountLedgerTransaction::where("account_ledger__transaction_id", $request->vo_no)->update(["date" => $request->date]);
        try {
            DB::beginTransaction();

            $type_id = 'journal_id';

            if (empty($request->account_id)) {

                foreach ($contra->joDemoDetails as $key => $details) {
                    $summary = LedgerSummary::where('ledger_id', $details->ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                    if ($summary) {
                        if ($details->drcr == 'Dr') {
                            $summary->update(['debit' =>  abs($summary->debit - $details->amount)]);
                            $this->deleteTransaction(
                                $contra,
                                'debit',
                                $request->date,
                                $details->vo_no,
                                $details->ledger_id,
                                $details->amount
                            );
                        } else {
                            $summary->update(['credit' => abs($summary->credit - $details->amount)]);
                            $this->deleteTransaction(
                                $contra,
                                'credit',
                                $request->date,
                                $details->vo_no,
                                $details->ledger_id,
                                $details->amount
                            );
                        }
                        $details->delete();
                    } else {
                        dd('something is wrong');
                    }
                }
            }

            if (($request->account_id)) {
                $oldLedgerId = [];

                foreach ($contra->joDemoDetails as $key => $details) {
                    array_push($oldLedgerId, $details['ledger_id']);
                }
                $deletedLedger = array_diff($oldLedgerId, $request->account_id);

                if (count($deletedLedger) > 0) {

                    foreach ($deletedLedger as $key => $value) {
                        $summary = LedgerSummary::where('ledger_id', $value)
                        ->where('financial_date', (new Helper)::activeYear())
                        ->first();
                        $details = $contra->joDemoDetails()->where('ledger_id', $value)->first();

                        if ($summary) {
                            if ($details->drcr == 'Dr') {
                                $summary->update(['debit' =>  abs($summary->debit - $details->amount) ]);
                                $this->deleteTransaction(
                                    $contra,
                                    'debit',
                                    $request->date,
                                    $details->vo_no,
                                    $details->ledger_id,
                                    $details->amount
                                );
                            } else {
                                $summary->update(['credit' => abs($summary->credit - $details->amount) ]);
                                $this->deleteTransaction(
                                    $contra,
                                    'credit',
                                    $request->date,
                                    $details->vo_no,
                                    $details->ledger_id,
                                    $details->amount
                                );
                            }
                            $details->delete();
                        } else {
                            dd('something is wrong');
                        }
                    }
                }
            }



            if (!empty($request->new_account_id)) {
                foreach ($request->new_account_id as $key => $value) {

                    $id_row = rand(111111, 999999) . '-' . date('y');

                    $row = DemoContraJournalAddlist::create([
                        'id_row' => $id_row,
                        $type_id => $contra->id,
                        'ledger_id' => $value,
                        'vo_no' => $request->vo_no,
                        'date' =>  $request->date,
                        'page_name' => $request->page_name,
                        'account_name' => $value,
                        'drcr' => $request->new_drcr_text[$key],
                        'amount' => $request->new_amount[$key],
                        'note' => $request->new_note[$key],
                    ]);
                    $summary = LedgerSummary::where('ledger_id', $value)
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();

                    if ($summary) {
                        if ($row->drcr == 'Dr') {
                            $summary->update(['debit' => $row->amount + $summary->debit]);
                            $this->transaction(
                                $contra,
                                'debit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        } else {
                            $summary->update(['credit' => $row->amount + $summary->credit]);
                            $this->transaction(
                                $contra,
                                'credit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        }
                    } else {
                        if ($row->drcr == "Dr") {
                            LedgerSummary::updateOrCreate(['ledger_id' => $row->ledger_id, 'financial_date' => (new Helper)::activeYear()], [
                                'debit' => $row->amount
                            ]);

                            $this->transaction(
                                $contra,
                                'debit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        } else {
                            LedgerSummary::updateOrCreate(['ledger_id' => $row->ledger_id,'financial_date' => (new Helper)::activeYear()], [
                                'credit' => $row->amount
                            ]);

                            $this->transaction(
                                $contra,
                                'credit',
                                $request->date,
                                $row->vo_no,
                                $row->ledger_id,
                                $row->amount
                            );
                        }
                    }
                }
            }
            if($request->print){
               return $this->print_journal_recepet($id);
           }
            (new LogActivity)->addToLog('Journal Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            dd($ex->getMessage(), $ex->getLine());
        }

        return back();
    }
    public function delete_journal_addlist($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        try {
            DB::beginTransaction();

            $contra = Journal::where("id", $id)->with('joDemoDetails', 'transaction')->first();

            foreach ($contra->joDemoDetails as $key => $details) {

                $summary = LedgerSummary::where('ledger_id', $details->ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($summary) {
                    if ($details->drcr == 'Dr') {
                        $summary->update(['debit' =>  abs($summary->debit - $details->amount) ]);
                        $this->deleteTransaction(
                            $contra,
                            'debit',
                            $contra->date,
                            $details->vo_no,
                            $details->ledger_id,
                            $details->amount
                        );
                    } else {
                        $summary->update(['credit' => abs($summary->credit - $details->amount) ]);
                        $this->deleteTransaction(
                            $contra,
                            'credit',
                            $contra->date,
                            $details->vo_no,
                            $details->ledger_id,
                            $details->amount
                        );
                    }
                    $details->delete();
                } else {
                    dd('something is wrong');
                }
            }
            $contra->delete();
            (new LogActivity)->addToLog('JOurnal Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->to('journal_addlist');
    }


    public function view_contra_recepet($id)
    {

        return view('MBCorporationHome.transaction.contra_addlist.view_contra_recepet', compact('id'));
    }

    public function print_contra_recepet($id)
    {
        return view('MBCorporationHome.transaction.contra_addlist.print_contra_recepet', compact('id'));
    }



    // common contra jornal demo  store Start..........................
    // common contra jornal demo  store Start..........................

    public function addOnDemo_Contra_Journal_list_store($request)
    {
        foreach ($request->new_account_id ?? $request->account_id as $key => $value) {

            $id_row = rand(111111, 999999) . '-' . date('y');
            DemoContraJournalAddlist::create([
                'id_row' => $id_row,
                'ledger_id' => $value,
                'vo_no' => $request->vo_no,
                'page_name' => $request->page_name,
                'account_name' => $value,
                'drcr' => $request->drcr_text[$key],
                'amount' => $request->amount[$key],
                'note' => $request->note[$key],
            ]);
        }
        return true;
    }

    public function contra_journaladd_new_fild($vo_no)
    {
        $data =    DemoContraJournalAddlist::where('vo_no', $vo_no)->with('ledger')->get();
        return response()->json($data);
    }

    public function demo_contra_journal_delete_field($id)
    {
        DemoContraJournalAddlist::where('id', $id)->delete();
        return true;
    }

    // common contra jornal demo  store End..........................
    // common contra jornal demo  store End..........................


    // common contra jornal add store Start..........................
    // common contra jornal add store Start..........................
    public function contra_journal_addlist_store(Request $request, Helper $helper)
    {
        
        $request->validate([
            'vo_no' => 'required|unique:journals',
            'page_name' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,

        ]);

        try {
            DB::beginTransaction();

            $data_add = Journal::create([
                'date' => $request->date,
                'page_name' => $request->page_name,
                'vo_no' => $request->vo_no,
            ]);

            $this->addOnDemo_Contra_Journal_list_store($request);

            $demoContraJournalAddlist = DemoContraJournalAddlist::where('vo_no', $request->vo_no)->get();

            if ($data_add->page_name == 'contra') {
                $this->checkType($data_add,  $demoContraJournalAddlist, 'contra_id', $request);
            } else {
                $this->checkType($data_add,  $demoContraJournalAddlist, 'journal_id', $request);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json($ex->getMessage());
        }
        if($request->page_name =='contra'){
            return redirect()->to('contra_addlist');
        }else {
            return redirect()->to('journal_addlist');
        }

    }

    public function checkType($data_add, $demoContraJournalAddlist, $type_id, $request)
    {

        foreach ($demoContraJournalAddlist  as $row) {
            $row->update([$type_id =>  $data_add->id]);

            $summary = LedgerSummary::where('ledger_id', $row->ledger_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($summary) {
                if ($row->drcr == 'Dr') {
                    $summary->update(['debit' => $row->amount + $summary->debit]);
                    $this->transaction(
                        $data_add,
                        'debit',
                        $request->date,
                        $row->vo_no,
                        $row->ledger_id,
                        $row->amount
                    );
                } else {
                    $summary->update(['credit' => $row->amount + $summary->credit]);
                    $this->transaction(
                        $data_add,
                        'credit',
                        $request->date,
                        $row->vo_no,
                        $row->ledger_id,
                        $row->amount
                    );
                }
            } else {
                if ($row->drcr == "Dr") {
                    LedgerSummary::updateOrCreate(['ledger_id' => $row->ledger_id, 'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $row->amount
                    ]);

                    $this->transaction(
                        $data_add,
                        'debit',
                        $request->date,
                        $row->vo_no,
                        $row->ledger_id,
                        $row->amount
                    );
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $row->ledger_id,'financial_date' => (new Helper)::activeYear()], [
                        'credit' => $row->amount
                    ]);

                    $this->transaction(
                        $data_add,
                        'credit',
                        $request->date,
                        $row->vo_no,
                        $row->ledger_id,
                        $row->amount
                    );
                }
            }
        }
    }
    public function transaction($data, $type, $date, $vo_no, $ledger_id, $amount)
    {

        $ledger = AccountLedger::where('id', $ledger_id)->first();
        AccountLedgerTransaction::create([
            'ledger_id' => $ledger->id,
            'account_ledger_id' => $ledger->account_ledger_id,
            'account_name' => $ledger->account_name,
            'date'      => $data->date,
            'account_ledger__transaction_id' => $data->vo_no,
            $type =>    $amount,
        ]);

        $data->transaction()->Create(
            [
                'vo_no' => $vo_no,
                'date'      => $date,
                'ledger_id' => $ledger_id,
                $type   => $amount
            ]
        );
    }
    public function deleteTransaction($data, $type, $date, $vo_no, $ledger_id, $amount)
    {
        
        try {
            $ledger = AccountLedger::where('id', $ledger_id)->first();
            $LT =  AccountLedgerTransaction::where([
                ['account_name', $ledger->account_name],
                ['account_ledger__transaction_id', $vo_no],
                ['ledger_id', $ledger->id],
                [$type, $amount],
            ])->delete();

            $T = Transaction::where([
                ['vo_no', $vo_no],
                ['transactionable_id', $data->id],
                ['transactionable_type', 'App\Journal'],
                ['ledger_id', $ledger->id],
                [$type, $amount],
            ])->delete();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }


    // common contra jornal add store end..........................
    // common contra jornal add store end..........................


    //add contra_addlist .....................
    //add contra_addlist .....................
    public function journal_addlist()
    {
        $Journal = Journal::where("page_name", 'journal')->orderby('date', 'desc')->paginate(10);
        return view('MBCorporationHome.transaction.journal_addlist.index', compact('Journal'));
    }
    public function journa_addlist_form()
    {
        return view('MBCorporationHome.transaction.journal_addlist.journal_addlist_form');
    }



    public function edit_journal_addlist($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $mes = "";
        $contra = Journal::where("id", $id)->get();
        return view('MBCorporationHome.transaction.journal_addlist.edit_journal_addlist', compact('contra', 'mes'));
    }

    public function view_journal_recepet($vo_no)
    {
        $vo_no = $vo_no;
        return view('MBCorporationHome.transaction.journal_addlist.view_journal_recepet', compact('vo_no'));
    }

    public function print_journal_recepet($vo_no)
    {
        $vo_no = $vo_no;
        return view('MBCorporationHome.transaction.journal_addlist.print_journal_recepet', compact('vo_no'));
    }
}
