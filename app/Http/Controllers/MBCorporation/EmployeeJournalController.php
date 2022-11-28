<?php

namespace App\Http\Controllers\MBCorporation;

use App\AccountGroup;
use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\EmployeeJournal;
use App\EmployeeJournalDetails;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\LedgerSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeJournalController extends Controller
{

    public function addondemocontrajournal_list_store(Request $request)
    {

        foreach ($request->amount as $key => $value) {

            $id_row = rand(1111111,9999999);
            $data_add =EmployeeJournalDetails::create([
                'id_row'        =>$id_row,
                'ledger_id'     =>$request->account_id[$key]  != 'null' ?$request->account_id[$key]:null,
                'vo_no'         =>$request->vo_no,
                'employee_id'   =>$request->employee_id[$key] != 'null'?$request->employee_id[$key]:null,
                'drcr'          =>$request->drcr_text[$key] == 'Dr' ? 1: 2,
                'amount'        =>$value,
                'note'          =>$request->note[$key],
            ]);
        }
        return true;
    }

    public function journal_add_new_field($vo_no)
    {

        $data =    EmployeeJournalDetails::where('vo_no',$vo_no)->with('ledger', 'employee')->get();
        return response()->json($data);
    }

    public function democontrajournal_delete_fild($id_row)
    {

        $data =EmployeeJournalDetails::where('id_row',$id_row)->delete();
        return response()->json($data);
    }

    public function contra_journal_addlist_store(Request $request, Helper $helper)
    {

        $request->validate([
            'vo_no' => 'required|unique:employee_journals,vo_no',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,

        ]);

        try {
            DB::beginTransaction();

            $data_add =EmployeeJournal::create([
                'date'=>$request->date,
                'page_name'=>$request->page_name,
                'vo_no'=>$request->vo_no,
            ]);

             $this->addondemocontrajournal_list_store($request);
            $demoContraJournalAddlist = EmployeeJournalDetails::where('vo_no' , $request->vo_no)->get();

            $this->checkType($data_add,  $demoContraJournalAddlist,  $request);
            (new LogActivity)->addToLog('EmployeeJournalDetails Created.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json($ex->getMessage());
        }
        return back();

    }

    public function checkType( $data_add, $demoContraJournalAddlist, $request)
    {
        foreach($demoContraJournalAddlist  as $row){

            if($row->ledger_id){
                $summary = LedgerSummary::where('ledger_id' , $row->ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();

                if($summary){
                    if($row->drcr == 1){
                        $summary->update(['debit' => $row->amount + $summary->debit ]);
                        $this->transaction($data_add, 'debit',  $request->date, $row->vo_no, $row->ledger_id,
                        $row->amount);
                    }else{
                        $summary->update(['credit' => $row->amount + $summary->credit ]);
                        $this->transaction($data_add, 'credit',  $request->date, $row->vo_no, $row->ledger_id,
                        $row->amount);
                    }
                }else{
                    if($row->drcr == 1){
                        LedgerSummary::updateOrCreate(['ledger_id' =>$row->ledger_id,'financial_date' => (new Helper)::activeYear()],[
                            'debit' => $row->amount
                        ]);

                        $this->transaction($data_add, 'debit',  $request->date, $row->vo_no, $row->ledger_id,
                        $row->amount);

                    }else{
                        LedgerSummary::updateOrCreate(['ledger_id' =>$row->ledger_id,'financial_date' => (new Helper)::activeYear()],[
                            'credit' => $row->amount
                        ]);
                        $this->transaction($data_add, 'credit',  $request->date, $row->vo_no, $row->ledger_id,
                        $row->amount);
                    }
                }
            }
            if($row->employee_id){
                $summary = LedgerSummary::where('ledger_id' , $row->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    if($row->drcr == 1){
                        $summary->update(['debit' => $row->amount + $summary->debit ]);
                    }else{
                        $summary->update(['credit' => $row->amount + $summary->credit ]);
                    }
                }else{
                    if($row->drcr == 1){
                         LedgerSummary::updateOrCreate(['ledger_id' =>$row->employee_id,'financial_date' => (new Helper)::activeYear()],[
                            'debit' => $row->amount
                        ]);


                    }else{
                        LedgerSummary::updateOrCreate(['ledger_id' =>$row->employee_id,'financial_date' => (new Helper)::activeYear()],[
                            'credit' => $row->amount
                        ]);
                    }
                }
            }

        }
    }
    public function transaction($data, $type, $date, $vo_no, $ledger_id, $amount)
    {

        $ledger = AccountLedger::where('id', $ledger_id)->first();
        AccountLedgerTransaction::create([
            'ledger_id'=>$ledger->id,
            'account_ledger_id'=>$ledger->account_ledger_id,
            'account_name'=>$ledger->account_name,
            'account_ledger__transaction_id'=>$data->vo_no,
            $type =>   $amount,
        ]);

        $data->transaction()->updateOrCreate(
        [
            'vo_no' => $vo_no,
            'date'      => $date,
            'ledger_id' => $ledger_id,
            $type   => $amount
        ]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Journal =EmployeeJournal::orderby('date')->get();
        return view('MBCorporationHome.employee_journal.index', compact('Journal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
                    array_push( $ledgerArray,$ledger->accountLedgers[$i]->account_name);
                    array_push( $ledgerIdArray,$ledger->accountLedgers[$i]->id);
                }
            }
        }

        $ledgers = array_combine( $ledgerIdArray,$ledgerArray );

        return view('MBCorporationHome.employee_journal.journal_addlist_form', compact('ledgers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $Journal =EmployeeJournal::orderby('date')->get();
        $journal =EmployeeJournal::where("id",$id)->first();
        $expensesLedger = AccountGroup::where('account_group_nature', 'Expenses')
        ->with(['accountLedgers'])
        ->get();
        $ledgerArray = [];
        $ledgerIdArray = [];
        foreach ($expensesLedger as $key => $ledger) {
            if(count($ledger->accountLedgers) > 0 ){
                for ($i=0; $i < count($ledger->accountLedgers); $i++) {
                    array_push( $ledgerArray,$ledger->accountLedgers[$i]->account_name);
                    array_push( $ledgerIdArray,$ledger->accountLedgers[$i]->id);
                }
            }
        }

        $ledgers = array_combine( $ledgerIdArray,$ledgerArray );
        $mes = "";
        return view('MBCorporationHome.employee_journal.edit_journal_addlist', compact('ledgers', 'journal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
