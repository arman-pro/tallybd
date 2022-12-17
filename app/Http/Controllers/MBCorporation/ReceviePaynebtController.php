<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\LedgerSummary;
use App\Receive;
use App\Payment;
use App\Traits\SMS;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ImportPayment;
use Maatwebsite\Excel\Facades\Excel;
use Datatables;

class ReceviePaynebtController extends Controller
{
    use SMS;

    public function send_receive_sms($id)
    {

        try {
        $person =  Receive::whereId($id)->with('accountMode.summary')->first();
        if(optional($person->accountMode)->account_ledger_phone){
            $mobile = optional($person->accountMode)->account_ledger_phone;
            if($person->accountMode->summary->grand_total > 1){
                $balance = $person->accountMode->summary->grand_total.' DR,';
            }else{
                $balance = $person->accountMode->summary->grand_total*(-1).' CR,';

            }
            $text = 'Receive Dated:'.date('d-m-Y', strtotime($person->date)).',Amount:Tk. '.$person->amount.',Closing Bal: '.$balance.'Thank You!';
            SMS::sendSMS($mobile, $text);
        }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }
    public function send_payment_sms($id)
    {

        try {
        $person =  Payment::whereId($id)->with('accountMode')->first();
        if(optional($person->accountMode)->account_ledger_phone){
            $mobile = optional($person->accountMode)->account_ledger_phone;
            if($person->accountMode->summary->grand_total > 1){
                $balance = $person->accountMode->summary->grand_total.' DR,';
            }else{
                $balance = $person->accountMode->summary->grand_total*(-1).' CR,';
            }
            $text = 'Payment Dated:'.date('d-m-Y', strtotime($person->date)).',Amount:Tk. '.$person->amount.',Closing Bal: '.$balance.'Thank You!';
            SMS::sendSMS($mobile, $text);
        }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }

    public function recevied_addlist_datatables()
    {
        $receive = Receive::with(['paymentMode', 'accountMode'])->orderBy('date', 'desc');
        return Datatables()->eloquent($receive)
        ->addIndexColumn()
        ->editColumn('date', function(Receive $receive) {
            return "<a href='javascript:void(0)' class='copy_text' data-text='".$receive->date."' role='button'>".date('d-m-y', strtotime($receive->date))."</a>";
        })
        ->editColumn('amount', function(Receive $receive) {
            return new_number_format($receive->amount);
        })
        ->editColumn('description', function(Receive $receive) {
            return $receive->description ? "<span class='badge bg-info view_message' role='button' data-message='".$receive->description."'>View Message</span>" : "N/A";
        })
        ->addColumn('action', function(Receive $receive) {
            return make_action_btn([
                '<a href="'.route("view_recevie_recepet", ['vo_no' => $receive->vo_no]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_recevie_addlist",['vo_no' => $receive->vo_no]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="'.route("send_recevie_sms",['id' => $receive->id]).'" class="dropdown-item"><i class="far fa-envelope"></i> Send SMS</a>',
                '<a href="javascript:void(0)" data-id="'.$receive->id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("print_receive_recepet", ['vo_no' => $receive->vo_no]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['action', 'description', 'date'])
        ->toJson(true);
    }

    //add recevied_addlist .....................
    public function recevied_addlist(Request $request)
    {
        if($request->ajax()) {
            return $this->recevied_addlist_datatables();
        }
        return view('MBCorporationHome.transaction.recevied_addlist.index');
    }
    public function recevied_addlist_form()
    {
        return view('MBCorporationHome.transaction.recevied_addlist.recevied_addlist_form');
    }


    public function store_recived_addlist(Request $request, Helper $helper)
    {
        $validatedData = $request->validate([
            'date' => 'required',
            'vo_no' => 'required|unique:receives',
            'payment_mode_ledger_id' => 'required',
            'account_name_ledger_id' => 'required',
            'amount' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);

        try {
            DB::beginTransaction();
             $account_ledger = AccountLedger::where('id', $request->account_name_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->account_ledger_id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'credit' => $request->amount,
            ]);


            $receive = Receive::create([
                'date' => $request->date,
                'vo_no' => $request->vo_no,
                'payment_mode_ledger_id' => $request->payment_mode_ledger_id,
                'account_name_ledger_id' => $request->account_name_ledger_id,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            $account_ledger_to = AccountLedger::where('id', $request->payment_mode_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger_to->id,
                'account_ledger_id' => $account_ledger_to->account_ledger_id,
                'account_name' =>$account_ledger_to->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'debit' => $request->amount,
            ]);

            if($request->payment_mode_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$request->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();

                if($summary){
                    $summary->update(['debit' => $request->amount + $summary->debit ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->payment_mode_ledger_id,'financial_date' => (new Helper)::activeYear()],[
                            'debit' => $request->amount
                        ]);
                }

            }

            if($request->account_name_ledger_id){

                $summary = LedgerSummary::where('ledger_id' ,$request->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' => $request->amount + $summary->credit ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_name_ledger_id,'financial_date' => (new Helper)::activeYear()],[
                            'credit' => $request->amount
                        ]);
                }
            }
            (new LogActivity)->addToLog('Received Added.');
            
            if($request->has('send_sms') && $request->send_sms == 'yes') {
                // send sms
                $this->send_receive_sms($receive->id);
            }
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if($request->print){
            $vo_no = $receive->vo_no;
             return redirect()->route('print_receive_recepet', ['vo_no' => $vo_no]);
            return view('MBCorporationHome.transaction.recevied_addlist.print_receive_recepet', compact('vo_no'));
        }
        return redirect()->to('recevied_addlist');

    }

    public function delete_recevie_addlist($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            $receive =  Receive::where('id', $id)->first();
            if($receive->payment_mode_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$receive->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['debit' =>  $summary->debit - $receive->amount  ]);
                }
            }
            if($receive->account_name_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$receive->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' =>   $summary->credit - $receive->amount ]);
                }
            }
            AccountLedgerTransaction::where('account_ledger__transaction_id', $receive->vo_no)->delete();
            Receive::where('id', $id)->delete();
            (new LogActivity)->addToLog('Received Deleted.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);


        // return redirect()->to('recevied_addlist');
    }

    public function edit_recevie_addlist($vo_no)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $receive = Receive::where('vo_no', $vo_no)->with(['paymentMode', 'accountMode'])->first();
        return view('MBCorporationHome.transaction.recevied_addlist.edit_recevie_addlist', compact('receive'));
    }

    public function update_recived_addlist(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'vo_no' => 'required',
            'account_name_ledger_id' => 'required',
            'payment_mode_ledger_id' => 'required',
            'amount' => 'required',
        ]);
        // return $request->all();
        try {
            DB::beginTransaction();
            $receive =Receive::where('id', $id)->first();

            if($receive->payment_mode_ledger_id == $request->payment_mode_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$receive->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                $summary->update(['debit' => abs($request->amount + $summary->debit -$receive->amount) ]);

            }else{
                $oldSummary = LedgerSummary::where('ledger_id' ,$receive->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                $oldSummary->update(['debit' => abs($oldSummary->debit - $receive->amount) ]);

                $summary = LedgerSummary::where('ledger_id' ,$request->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['debit' => $request->amount + $summary->debit]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->payment_mode_ledger_id,
                    'financial_date'=> (new Helper)::activeYear() ],[
                            'debit' => $request->amount
                        ]);
                }

            }

            if($receive->account_name_ledger_id == $request->account_name_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$receive->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                $summary->update(['credit' => abs($request->amount + $summary->credit - $receive->amount) ]);

            }else{
                $oldSummary = LedgerSummary::where('ledger_id' ,$receive->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                $oldSummary->update(['credit' => abs($oldSummary->credit - $receive->amount) ]);
                $summary = LedgerSummary::where('ledger_id' ,$request->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' => $request->amount + $summary->credit]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_name_ledger_id,
                    'financial_date' => (new Helper)::activeYear()],[
                            'credit' => $request->amount
                        ]);
                }
            }
            
            AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->delete();
            
            $account_ledger = AccountLedger::where('id', $request->account_name_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->account_ledger_id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'credit' => $request->amount,
            ]);

            $account_ledger_to = AccountLedger::where('id', $request->payment_mode_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger_to->id,
                'account_ledger_id' => $account_ledger_to->account_ledger_id,
                'account_name' =>$account_ledger_to->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'debit' => $request->amount,
            ]);
            
            
            // AccountLedgerTransaction::where('account_ledger__transaction_id', $receive->vo_no)
            //     ->update([
            //         'credit' => $request->amount,
            //         'debit' => 0,
            //     ]);
            // AccountLedgerTransaction::where('account_ledger__transaction_id', $receive->vo_no)->update([
            //     'debit' => $request->amount,
            //     'credit' => 0,
            // ]);


            
            $receive ->update([
                'date' => $request->date,
                'payment_mode_ledger_id' => $request->payment_mode_ledger_id,
                'account_name_ledger_id' => $request->account_name_ledger_id,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);
            (new LogActivity)->addToLog('Received Updated.');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if($request->print){
            $vo_no = $receive->vo_no;
            return view('MBCorporationHome.transaction.recevied_addlist.print_receive_recepet', compact('vo_no'));
        }

        return redirect()->to('recevied_addlist');
    }

    public function print_receive_recepet($vo_no)
    {
        $vo_no = $vo_no;
        $receive = Receive::where('vo_no', $vo_no)->first();
        return view('MBCorporationHome.transaction.recevied_addlist.print_report', compact('vo_no', 'receive'));
    }


    public function print_receive_recepet_2($vo_no)
    {
        $vo_no = $vo_no;
        return view('MBCorporationHome.transaction.recevied_addlist.print_received_2', compact('vo_no'));
    }

    public function view_recevie_recepet($vo_no)
    {
        $receive = Receive::with(['paymentMode', 'accountMode'])
        ->where('vo_no', $vo_no)
        ->first();
        $vo_no = $vo_no;
        return view('MBCorporationHome.transaction.recevied_addlist.view_receive_recepet', compact('vo_no', 'receive'));
    }

    public function payment_datatables()
    {
        $search = request()->search['value'];
        $payments = Payment::with(['paymentMode', 'accountMode'])->orderby('date', 'desc');
        $datatables = Datatables()->eloquent($payments);
        if($search && $d=strtotime($search)) {
            $datatables=$datatables->filter(function($query)use($d) {
                $query->whereDate('date', date('Y-m-d',$d));
            });
        }
        
        return $datatables->addIndexColumn()
        ->editColumn("date", function(Payment $payment) {
            return date('d-m-y', strtotime($payment->date));
        })
        ->editColumn('amount', function(Payment $payment) {
            return new_number_format($payment->amount ?? 0);
        })
        ->editColumn('description', function(Payment $payment) {
            return $payment->description ? "<span class='badge bg-info view_message' role='button' data-message='".$payment->description."'>View Message</span>" : "N/A";
        })
        ->addColumn('action', function(Payment $payment) {
            return make_action_btn([
                '<a href="'.route("view_payment_recepet", ['vo_no' => $payment->vo_no]).'" class="dropdown-item"><i class="far fa-eye"></i> View</a>',
                '<a href="'.route("edit_payment_addlist",['vo_no' => $payment->vo_no]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="'.route("send_payment_sms",['id' => $payment->id]).'" class="dropdown-item"><i class="far fa-envelope"></i> Send SMS</a>',
                '<a href="javascript:void(0)" data-id="'.$payment->id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
                '<a target="_blank" href="'.route("print_payment_recepet", ['vo_no' => $payment->vo_no]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
            ]);
        })
        ->rawColumns(['description', 'action'])
        ->make(true);
    }

    //add Payment_addlist .....................
    public function payment_addlist(Request $request)
    {
        if($request->ajax()) {
            return $this->payment_datatables();
        }
        return view('MBCorporationHome.transaction.payment_addlist.index');
    }
    public function payment_addlist_form()
    {
        return view('MBCorporationHome.transaction.payment_addlist.payment_addlist_form');
    }
    
    
    // import payment
    
    public function import_payment() {
        return view('MBCorporationHome.transaction.payment_addlist.import_payment');
    }

    // store import payment data
    public function store_import_payment(Request $request) {
        $request->validate([
            'payment_import' => 'required|mimes:xlsx',
        ]);
        
        $datas = Excel::toArray(new ImportPayment, $request->payment_import)[0];
        // $UNIX_DATE = (44595.0 - 25569) * 86400;
        // echo gmdate("d-m-Y", $UNIX_DATE);
        foreach($datas as $data) {
            try {
                $data = (object)$data;
                
                $UNIX_DATE = ($data->date - 25569) * 86400;
                $date = gmdate("Y-m-d", $UNIX_DATE);
                $request->request->add(['date' => $date]);
        
                DB::beginTransaction();
                
                $account_ledger = AccountLedger::where('account_name', $data->ledger_name)->first();
                $payment_ledger = AccountLedger::where('account_name', $data->ledger_name)->where('payment',true)->first();
                
                if($payment_ledger){
                    $summary = LedgerSummary::where('ledger_id' ,$payment_ledger->id)
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();
                    if($summary){
                        $summary->update(['credit' => $data->amount + $summary->credit ]);
                    }else{
                        LedgerSummary::updateOrCreate(['ledger_id' =>$payment_ledger->id,'financial_date'=> (new Helper)::activeYear()],[
                                'credit' => $data->amount
                            ]);
                    }
    
                }
    
    
                if($account_ledger){
                    $summary = LedgerSummary::where('ledger_id', $account_ledger->id)
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();
                    if($summary){
                        $summary->update(['debit' => $data->amount + $summary->debit ]);
                    }else{
                        LedgerSummary::updateOrCreate(['ledger_id' =>$account_ledger->id,
                            'financial_date'=> (new Helper)::activeYear()],[
                                'debit' => $request->amount
                            ]);
                    }
    
                }
                
                $account_ledger = AccountLedger::where('id', $account_ledger->id)->first();
                
                $vo_no = Helper::IDGenerator(new Payment, 'vo_no', 4, 'Pa');
                                                    
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->id,
                    'account_name' => $account_ledger->account_name,
                    'account_ledger__transaction_id' => $vo_no,
                    'debit' => $data->amount,
                    'date' => $date,
                ]);
    
                $payment= Payment::create([
                    'date' => $date,
                    'vo_no' => $vo_no,
                    'payment_mode_ledger_id' => $payment_ledger->id,
                    'account_name_ledger_id' => $account_ledger->id,
                    'amount' => $data->amount,
                    'description' => $data->note,
                ]);
    
                $account_ledger_to = AccountLedger::where('id', $payment_ledger->id)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger_to->id,
                    'account_ledger_id' => $account_ledger_to->id,
                    'account_name' => $account_ledger_to->account_name,
                    'account_ledger__transaction_id' => $vo_no,
                    'credit' => $data->amount,
                    'date' => $date,
                ]);
                (new LogActivity)->addToLog('Payment Created.');
    
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }
        return redirect()->route('importpayment')->with('message','Import Successfull');
    }


   public function store_payment_addlist(Request $request, Helper $helper)
    {

        $validatedData = $request->validate([
            'date' => 'required',
            'vo_no' => 'required|unique:payments',
            'payment_mode_ledger_id' => 'required',
            'account_name_ledger_id' => 'required',
            'amount' => 'required',
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
        ]);
        try {

            DB::beginTransaction();
            
            if($request->payment_mode_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$request->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' => $request->amount + $summary->credit ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->payment_mode_ledger_id,'financial_date'=> (new Helper)::activeYear()],[
                            'credit' => $request->amount
                        ]);
                }

            }


            if($request->account_name_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$request->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['debit' => $request->amount + $summary->debit ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_name_ledger_id,
                        'financial_date'=> (new Helper)::activeYear()],[
                            'debit' => $request->amount
                        ]);
                }

            }
            $account_ledger = AccountLedger::where('id', $request->account_name_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'debit' => $request->amount,
            ]);

            $payment= Payment::create([
                'date' => $request->date,
                'vo_no' => $request->vo_no,
                'payment_mode_ledger_id' => $request->payment_mode_ledger_id,
                'account_name_ledger_id' => $request->account_name_ledger_id,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);

            $account_ledger_to = AccountLedger::where('id', $request->payment_mode_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger_to->id,
                'account_ledger_id' => $account_ledger_to->id,
                'account_name' => $account_ledger_to->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'credit' => $request->amount,
            ]);
            
             // send sms
            if($request->has('send_sms') && $request->send_sms == 'yes') {
                $this->send_payment_sms($payment->id);
            }
            
            (new LogActivity)->addToLog('Payment Created.');
            
           
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if($request->print){
            return redirect()->route('print_payment_recepet', ['vo_no' => $payment->vo_no]);
            return  $this->print_payment_recepet( $payment->vo_no);
        }
        return redirect()->to('payment_addlist');

    }

    public function delete_payment_addlist($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            $payment= Payment::where('id', $id)->first();
            if($payment->payment_mode_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$payment->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' => $summary->credit - $payment->amount]);
                }
            }

            if($payment->account_name_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$payment->account_name_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['debit' =>  $summary->debit - $payment->amount]);
                }
            }
            AccountLedgerTransaction::where('account_ledger__transaction_id', $payment->vo_no)->delete();
            Payment::where('id', $id)->delete();
            (new LogActivity)->addToLog('Payment Deleted.');

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

        // return redirect()->to('payment_addlist');
    }

    public function edit_payment_addlist($vo_no)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}

        $Payment = Payment::where('vo_no', $vo_no)->get();
        return view('MBCorporationHome.transaction.payment_addlist.edit_payment_addlist', compact('Payment'));
    }

    public function update_payment_addlist(Request $request,Helper $helper, $vo_no)
    {
        $request->validate([
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
            'vo_no' => 'required',
            'payment_mode_ledger_id' => 'required',
            'account_name_ledger_id' => 'required',
            'amount' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::where('vo_no', $vo_no)->first();

            // payment_mode_ledger_id
            if($payment->payment_mode_ledger_id == $request->payment_mode_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$payment->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                $summary->update(['credit' => $request->amount + $summary->credit - $payment->amount]);

            }else{
                $oldSummary = LedgerSummary::where('ledger_id' ,$payment->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                $oldSummary->update(['credit' => $oldSummary->credit - $payment->amount ]);

                $summary = LedgerSummary::where('ledger_id' ,$request->payment_mode_ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($summary){
                    $summary->update(['credit' => $request->amount + $summary->credit]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->payment_mode_ledger_id,'financial_date' => (new Helper)::activeYear()],
                    ['credit' => $request->amount]);
                }

            }
            if($payment->account_name_ledger_id == $request->account_name_ledger_id){
                $summary = LedgerSummary::where('ledger_id' ,$payment->account_name_ledger_id)->first();
                $summary->update(['debit' => $request->amount + $summary->debit - $payment->amount]);

            }else{
                $oldSummary = LedgerSummary::where('ledger_id' ,$payment->account_name_ledger_id)->first();
                $oldSummary->update(['debit' => $oldSummary->debit - $payment->amount ]);

                $summary = LedgerSummary::where('ledger_id' ,$request->account_name_ledger_id)->first();
                if($summary){
                    $summary->update(['debit' => $request->amount + $summary->debit]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' =>$request->account_name_ledger_id,'financial_date'=> (new Helper)::activeYear()],[
                            'debit' => $request->amount
                        ]);
                }

            }
            
            AccountLedgerTransaction::where('account_ledger__transaction_id', $vo_no)->delete();
            
           $account_ledger = AccountLedger::where('id', $request->account_name_ledger_id)->first();
           
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger->id,
                'account_ledger_id' => $account_ledger->id,
                'account_name' => $account_ledger->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'debit' => $request->amount,
            ]);
            
                
            $account_ledger_to = AccountLedger::where('id', $request->payment_mode_ledger_id)->first();
            AccountLedgerTransaction::create([
                'ledger_id' => $account_ledger_to->id,
                'account_ledger_id' => $account_ledger_to->id,
                'account_name' => $account_ledger_to->account_name,
                'account_ledger__transaction_id' => $request->vo_no,
                'credit' => $request->amount,
            ]);
            

            $payment->update([
                'date' => $request->date,
                'payment_mode_ledger_id' => $request->payment_mode_ledger_id,
                'account_name_ledger_id' => $request->account_name_ledger_id,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);
           
            
            (new LogActivity)->addToLog('Payment Updated.');
            if($request->print){
                return $this->print_payment_recepet( $payment->vo_no);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getLine(), $ex->getMessage());
        }


        return redirect()->to('payment_addlist');
    }


    public function print_payment_recepet($vo_no)
    {
        $payment =  Payment::with(['paymentMode', 'accountMode'])
        ->where('vo_no', $vo_no)
        ->first();
        $vo_no = $vo_no;
        return view('MBCorporationHome.transaction.payment_addlist.print_report', compact('vo_no','payment'));
    }

    public function view_payment_recepet($vo_no)
    {
        $payment =  Payment::with(['paymentMode', 'accountMode'])
        ->where('vo_no', $vo_no)
        ->first();
        $vo_no = $vo_no;
        return view('MBCorporationHome.transaction.payment_addlist.view_payment_recepet', compact('vo_no', 'payment'));
    }
    
    public function ledgerValue($ledger_id)
    {
        $summary = LedgerSummary::where('ledger_id' ,$ledger_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
        return $summary->grand_total;
    }
}
