<?php

namespace App\Http\Controllers\MBCorporation;

use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\Employee;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\LedgerSummary;
use App\SalaryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\Help;
use Session;
use Datatables;
use App\Traits\SMS;

class SalaryPaymentController extends Controller
{
     use SMS;
    public function datatables() 
    {
        $salaries = SalaryPayment::where('payment_by', '>', 0)->with(['payment', 'createdBy', 'employee'])->orderBy('date', 'desc');
        return Datatables()->eloquent($salaries)
        ->addIndexColumn()
        ->editColumn('date', function(SalaryPayment $salary_payment) {
           return "<a role='button' class='copy_text' href='javascript:void(0)' data-text='".$salary_payment->date."'>".date('d-m-y', strtotime($salary_payment->date ?? ''))."</a>";
        })
        ->editColumn('name', function(SalaryPayment $salary_payment) {
            return optional($salary_payment->employee)->name ?? "N/A";
        })
        ->editColumn('amount', function(SalaryPayment $salary_payment) {
            return number_format($salary_payment->amount, 2);
        })
        ->addColumn('payment_by', function(SalaryPayment $salary_payment) {
            return optional($salary_payment->payment)->account_name ?? 'N/A';
        })
        ->addColumn('created_by', function(SalaryPayment $salary_payment) {
            return optional($salary_payment->createdBy)->name ?? 'N/A';
        })
        ->addColumn('action', function(SalaryPayment $salary_payment) {
            return make_action_btn([
                '<a href="'.route("salary_payment.print_salary_payment_recepet", ['vo_no' => $salary_payment->vo_no]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
                '<a href="'.route("salary_payment.edit",['id' => $salary_payment->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="javascript:void(0)" data-id="'.$salary_payment->id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
            ]);
        })
        ->rawColumns(['action', 'date'])
        ->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->datatables();
        }        
        return view('MBCorporationHome.salary_payment.index');
    }

    public function received_datatables() 
    {
        $salaries = SalaryPayment::where('receive_by', '>', 0)->with(['receive']);
        return Datatables()->eloquent($salaries)
        ->addIndexColumn()
        ->editColumn('date', function(SalaryPayment $salary_payment) {
            return date('d-m-y', strtotime($salary_payment->date));
        })
        ->addColumn("name", function(SalaryPayment $salary_payment) {
            return optional($salary_payment->employee)->name ?? 'N/A';
        })
        ->editColumn("amount", function(SalaryPayment $salary_payment) {
            return new_number_format($salary_payment->amount, 2);
        })
        ->addColumn("received_by", function(SalaryPayment $salary_payment) {
            return optional($salary_payment->receive)->account_name ?? 'N/A';
        })
        ->addColumn("payment_by", function(SalaryPayment $salary_payment) {
            return optional($salary_payment->createdBy)->name ?? 'N/A';
        })
        ->addColumn('action', function(SalaryPayment $salary_payment) {
            return make_action_btn([
                '<a href="'.route("salary_payment.print_salary_payment_recepet", ['vo_no' => $salary_payment->vo_no]).'" class="dropdown-item"><i class="fas fa-print"></i> Print</a>',
                '<a href="'.route("salary_payment.edit_receive",['id' => $salary_payment->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="javascript:void(0)" data-id="'.$salary_payment->id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
            ]);
        })
        ->make(true);
    }
    
    public function received(Request $request)
    {
        if($request->ajax()) {
            return $this->received_datatables();
        }
        return view('MBCorporationHome.salary_payment.received');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $mes = "";
        $paymentLedger = AccountLedger::where('payment', true)->get(['id', 'account_name']);
        $employees = Employee::whereStatus(true)->get(['id', 'name']);
        return view('MBCorporationHome.salary_payment.create', compact('mes', 'employees', 'paymentLedger'));
    }
    
    public function create_receive(Request $request)
    {
        $mes = "";
        $paymentLedger = AccountLedger::where('payment', true)->get(['id', 'account_name']);
        $employees = Employee::whereStatus(true)->get(['id', 'name']);
        return view('MBCorporationHome.salary_payment.create_receive', compact('mes', 'employees', 'paymentLedger'));
    }

    public function searchAccountSummary(Request $request)
    {

        $summary = 0;
        $type = '';
        $ledger = LedgerSummary::whereLedgerId($request->employee_id)
        ->where('financial_date', (new Helper)::activeYear())
        ->first();
        if ($ledger) {
            if ($ledger->grand_total > 0) {
                $type = '(Dr)';
                $summary = number_format($ledger->grand_total);
            } else {
                $type = '(Cr)';
                $summary = number_format($ledger->grand_total * -1);
            }
        }
        return response()->json(['summary' => $summary, 'type' => $type]);
    }
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Helper $helper)
    {
        $request->validate([
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from
        ]);
        try {
            DB::beginTransaction();

            if ($request->payment ) {
                $summary = LedgerSummary::where('ledger_id', $request->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();

                if ($summary) {
                    $summary->update(['debit' => abs($request->amount + $summary->debit)]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->employee_id,'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $request->amount
                    ]);
                }


                $paymentSummary = LedgerSummary::where('ledger_id', $request->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
               
                if ($paymentSummary) {
                    $paymentSummary->update(['credit' => floatval($request->amount) + floatval($paymentSummary->credit)]);

                } else {
                    $v =LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                        'credit' => $request->amount
                    ]);
                }

                $account_ledger = AccountLedger::where('id', $request->payment_by)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'account_ledger__transaction_id' => $request->vo_no,
                    'credit' =>   $request->amount,
                ]);
            }

            if ($request->receive_by) {
                $summary = LedgerSummary::where('ledger_id', $request->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($summary) {
                    $summary->update(['credit' =>floatval($request->amount) +floatval($summary->credit)]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->employee_id,
                    'financial_date' => (new Helper)::activeYear()], [
                        'credit' => $request->amount
                    ]);
                }
                $receiveSummary = LedgerSummary::where('ledger_id', $request->receive_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($receiveSummary) {
                    $receiveSummary->update(['debit' => floatval($request->amount) + floatval($receiveSummary->debit)]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->receive_by,
                    'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $request->amount
                    ]);
                }
                $account_ledger = AccountLedger::where('id', $request->receive_by)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'account_ledger__transaction_id' => $request->vo_no,
                    'debit' =>  $request->amount,
                ]);
            }
            $salary_payment = SalaryPayment::create($request->all());
            if($request->send_sms == 'yes') {
                $this->send_salary_sms($salary_payment->id);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex->getMessage(), $ex->getLine());
        }
        if($request->print) {
            return redirect()->route('salary_payment.print_salary_payment_recepet', ['vo_no' =>$salary_payment->vo_no]);
        }
        return redirect()->to('salary-payment/index')->with(['msg' => 'Data Inserted Successfully.']);
    }
    
    public function send_salary_sms($id)
    {
        try {
            $salary_payment =  SalaryPayment::whereId($id)->with(['employee', 'employee.summary'])->first();
            if(optional($salary_payment->employee)->mobile){
                $mobile = optional($salary_payment->employee)->mobile;
                if($salary_payment->employee->summary->grand_total > 1){
                    $balance = $salary_payment->employee->summary->grand_total.' DR,';
                }else{
                    $balance = $salary_payment->employee->summary->grand_total*(-1).' CR,';
                }
                $text = 'Payment Dated:'.date('d-m-Y', strtotime($salary_payment->date)).',Amount:Tk. '.$salary_payment->amount.',Closing Bal: '.$balance.'Thank You!';
                SMS::sendSMS($mobile, $text);
            }
        } catch (\Exception $ex) {
            return back()->with('mes', $ex->getMessage());
        }
        return back()->with('mes', 'Send SMS');
    }
    
    
    public function store_receive(Request $request, Helper $helper)
    {
        // dd($request->all());
        // payment_by ---> (cr) emp->dr
        // reveice_by ---> (dr) emp->cr
        // dd($request->all());
        $request->validate([
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from
        ]);
        try {
            DB::beginTransaction();

            if ($request->payment ) {
                $summary = LedgerSummary::where('ledger_id', $request->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();

                if ($summary) {
                    $summary->update(['debit' => abs($request->amount + $summary->debit)]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->employee_id,'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $request->amount
                    ]);
                }


                $paymentSummary = LedgerSummary::where('ledger_id', $request->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                // dd($paymentSummary, 238478239);
                if ($paymentSummary) {
                    $paymentSummary->update(['credit' => floatval($request->amount) + floatval($paymentSummary->credit)]);

                } else {
                    $v =LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                        'credit' => $request->amount
                    ]);
                }

                $account_ledger = AccountLedger::where('id', $request->payment_by)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'account_ledger__transaction_id' => $request->vo_no,
                    'credit' =>   $request->amount,
                ]);
            }

            if ($request->receive_by) {
                $summary = LedgerSummary::where('ledger_id', $request->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($summary) {
                    $summary->update(['credit' =>floatval($request->amount) +floatval($summary->credit)]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->employee_id,
                    'financial_date' => (new Helper)::activeYear()], [
                        'credit' => $request->amount
                    ]);
                }
                $receiveSummary = LedgerSummary::where('ledger_id', $request->receive_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($receiveSummary) {
                    $receiveSummary->update(['debit' => floatval($request->amount) + floatval($receiveSummary->debit)]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->receive_by,
                    'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $request->amount
                    ]);
                }
                $account_ledger = AccountLedger::where('id', $request->receive_by)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'account_ledger__transaction_id' => $request->vo_no,
                    'debit' =>  $request->amount,
                ]);
            }
            SalaryPayment::create($request->all());
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex->getMessage(), $ex->getLine());
        }
        return redirect()->to('salary-payment/received')->with(['msg' => 'Data Inserted Successfully.']);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $mes = "";
        $paymentLedger = AccountLedger::where('payment', true)->get(['id', 'account_name']);
        $employees = Employee::whereStatus(true)->get(['id', 'name']);
        $data= SalaryPayment::whereId($id)->first();
        return view('MBCorporationHome.salary_payment.edit', compact('mes', 'data','employees', 'paymentLedger'));
    }
    
    
    public function edit_receive($id)
    {
        $mes = "";
        $paymentLedger = AccountLedger::where('payment', true)->get(['id', 'account_name']);
        $employees = Employee::whereStatus(true)->get(['id', 'name']);
        $data= SalaryPayment::whereId($id)->first();
        return view('MBCorporationHome.salary_payment.edit_receive', compact('mes', 'data','employees', 'paymentLedger'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Helper $helper ,$id)
    {
       // payment_by ---> (cr) emp->dr
        // reveice_by ---> (dr) emp->cr
        // dd($request->all());
        $request->validate([
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from
        ]);
        try {
        DB::beginTransaction();
        $salaryPayment= SalaryPayment::whereId($id)->first();
        if ($request->payment_by && $request->payment_by ==  $salaryPayment->payment_by) {

            AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->where('credit', '>', 0)
            ->update(['credit' => $request->amount, 'date' => $request->date]);

            $summary = LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['debit' =>abs($request->amount + $summary->debit - $salaryPayment->amount)]);

            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $salaryPayment->employee_id,'financial_date' => (new Helper)::activeYear()], [
                    'debit' => $request->amount
                ]);
            }

            $paymentSummary = LedgerSummary::where('ledger_id', $request->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($paymentSummary) {
                $paymentSummary->update(['credit' => abs($request->amount + $paymentSummary->credit - $salaryPayment->amount) ]);


            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $request->amount
                ]);
            }

        }elseif($request->payment_by != $salaryPayment->payment_by && $salaryPayment->payment_by){
           $accountLedgerTransaction= AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)
           ->where('credit', '>', 0)->first();
            $account_ledger = AccountLedger::where('id', $request->payment_by)->first();
            if($accountLedgerTransaction){
                $accountLedgerTransaction->update([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'credit' =>  $request->amount,
                     'date' => $request->date,
                ]);
            }
            $summary = LedgerSummary::where('ledger_id', $request->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['debit' => abs($request->amount + $summary->debit - $salaryPayment->amount) ]);
            }
            $oldPaymentSummary = LedgerSummary::where('ledger_id', $salaryPayment->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($oldPaymentSummary) {
                $oldPaymentSummary->update(['credit' => abs($oldPaymentSummary->credit - $salaryPayment->amount) ]);
            }

            $newPaymentSummary = LedgerSummary::where('ledger_id', $request->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($newPaymentSummary) {
                $newPaymentSummary->update(['credit' => $request->amount + $newPaymentSummary->credit]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $request->amount
                ]);
            }
        }
        if ($request->receive_by && $request->receive_by ==  $salaryPayment->receive_by) {

            AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->where('debit', '>', 0)->first()
            ->update(['debit' => $request->amount,  'date' => $request->date,]);

            $summary = LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['credit' => abs($request->amount + $summary->credit - $salaryPayment->amount)]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->employee_id], [
                    'credit' => $request->amount
                ]);
            }
            $receiveSummary = LedgerSummary::where('ledger_id', $request->receive_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($receiveSummary) {
                $receiveSummary->update(['debit' => abs($request->amount + $receiveSummary->debit - $salaryPayment->amount)]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->receive_by], [
                    'debit' => $request->amount
                ]);
            }
        }elseif($request->receive_by !=  $salaryPayment->receive_by && $salaryPayment->receive_by){

            $accountLedgerTransaction= AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)
            ->where('debit', '>', 0)->first();
            $account_ledger = AccountLedger::where('id', $request->receive_by)->first();
            if($accountLedgerTransaction){
                $accountLedgerTransaction->update([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'debit' =>  $request->amount,
                     'date' => $request->date,
                ]);
            }

            $oldSummary = LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($oldSummary) {
                $oldSummary->update(['credit' => abs($request->amount + $summary->credit - $salaryPayment->amount)]);
            }

            $receiveSummary = LedgerSummary::where('ledger_id', $salaryPayment->receive_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($receiveSummary) {
                $receiveSummary->update(['debit' => abs($receiveSummary->credit - $salaryPayment->amount)]);
            }

            $newReceiveSummary = LedgerSummary::where('ledger_id', $request->receive_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($newReceiveSummary) {
                $newReceiveSummary->update(['debit' => floatval($request->amount) + floatval($newReceiveSummary->credit)]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->receive_by,'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $request->amount
                ]);
            }

        }
        $salaryPayment->update($request->all());
        DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->to('salary-payment/index')->with(['msg' => 'Data Deleted Successfully.']);
    }
    
    
    public function update_receive(Request $request,Helper $helper ,$id)
    {
       // payment_by ---> (cr) emp->dr
        // reveice_by ---> (dr) emp->cr
        // dd($request->all());
        $request->validate([
            'date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from
        ]);
        try {
        DB::beginTransaction();
        $salaryPayment= SalaryPayment::whereId($id)->first();
        if ($request->payment_by && $request->payment_by ==  $salaryPayment->payment_by) {

            AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->where('credit', '>', 0)
            ->update(['credit' => $request->amount]);

            $summary = LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['debit' =>floatval($request->amount) + floatval($summary->debit) - floatval($salaryPayment->amount)]);

            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $salaryPayment->employee_id,'financial_date' => (new Helper)::activeYear()], [
                    'debit' => $request->amount
                ]);
            }

            $paymentSummary = LedgerSummary::where('ledger_id', $request->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($paymentSummary) {
                $paymentSummary->update(['credit' => $request->amount + $paymentSummary->credit - $salaryPayment->amount]);


            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $request->amount
                ]);
            }

        }elseif($request->payment_by != $salaryPayment->payment_by && $salaryPayment->payment_by){
           $accountLedgerTransaction= AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)
           ->where('credit', '>', 0)->first();
            $account_ledger = AccountLedger::where('id', $request->payment_by)->first();
            if($accountLedgerTransaction){
                $accountLedgerTransaction->update([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'credit' =>  $request->amount,
                ]);
            }
            $summary = LedgerSummary::where('ledger_id', $request->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['debit' =>$request->amount + $summary->debit - $salaryPayment->amount]);
            }
            $oldPaymentSummary = LedgerSummary::where('ledger_id', $salaryPayment->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($oldPaymentSummary) {
                $oldPaymentSummary->update(['credit' => $oldPaymentSummary->credit - $salaryPayment->amount ]);
            }

            $newPaymentSummary = LedgerSummary::where('ledger_id', $request->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($newPaymentSummary) {
                $newPaymentSummary->update(['credit' => $request->amount + $newPaymentSummary->credit]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $request->amount
                ]);
            }
        }
        if ($request->receive_by && $request->receive_by ==  $salaryPayment->receive_by) {

            AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->where('debit', '>', 0)->first()
            ->update(['debit' => $request->amount]);

            $summary = LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['credit' => floatval($request->amount) + floatval($summary->credit) - floatval($salaryPayment->amount)]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->employee_id], [
                    'credit' => $request->amount
                ]);
            }
            $receiveSummary = LedgerSummary::where('ledger_id', $request->receive_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($receiveSummary) {
                $receiveSummary->update(['debit' => floatval($request->amount) + floatval($receiveSummary->debit) - floatval($salaryPayment->amount)]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->receive_by], [
                    'debit' => $request->amount
                ]);
            }
        }elseif($request->receive_by !=  $salaryPayment->receive_by && $salaryPayment->receive_by){

            $accountLedgerTransaction= AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)
            ->where('debit', '>', 0)->first();
            $account_ledger = AccountLedger::where('id', $request->receive_by)->first();
            if($accountLedgerTransaction){
                $accountLedgerTransaction->update([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'debit' =>  $request->amount,
                ]);
            }

            $oldSummary = LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($oldSummary) {
                $oldSummary->update(['credit' => floatval($request->amount) + floatval($summary->credit) - floatval($salaryPayment->amount)]);
            }

            $receiveSummary = LedgerSummary::where('ledger_id', $salaryPayment->receive_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();

            if ($receiveSummary) {
                $receiveSummary->update(['debit' => floatval($receiveSummary->credit) - floatval($salaryPayment->amount)]);
            }

            $newReceiveSummary = LedgerSummary::where('ledger_id', $request->receive_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($newReceiveSummary) {
                $newReceiveSummary->update(['debit' => floatval($request->amount) + floatval($newReceiveSummary->credit)]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $request->receive_by,'financial_date' => (new Helper)::activeYear()], [
                    'credit' => $request->amount
                ]);
            }

        }
        $salaryPayment->update($request->all());
        DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->to('salary-payment/received')->with(['msg' => 'Data Deleted Successfully.']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        
        // / payment_by ---> (cr) emp->dr
        // reveice_by ---> (dr) emp->cr
        try {
            DB::beginTransaction();

                $salaryPayment= SalaryPayment::whereId($id)->with(['payment', 'receive'])->first();
                $employeeLedger =LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();

                $receive_by = LedgerSummary::where('ledger_id', $salaryPayment->receive_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($receive_by){
                    $receive_by->update(['debit' => abs($receive_by->debit - $salaryPayment->amount) ]);
                    $employeeLedger->update(['credit' => abs($employeeLedger->credit - $salaryPayment->amount) ]);
                }
                $payment_by=  LedgerSummary::where('ledger_id', $salaryPayment->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($payment_by){
                    $payment_by->update(['credit' => abs($payment_by->credit - $salaryPayment->amount)]);
                    $employeeLedger->update(['debit' => abs($employeeLedger->debit - $salaryPayment->amount)]);
                }
                AccountLedgerTransaction::where('account_ledger__transaction_id', $salaryPayment->vo_no)->delete();

                $salaryPayment->delete();
                DB::commit();
            } catch (\Exception $ex) {
            DB::rollBack();

            return back()->with(['msg' => $ex->getMessage()]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
        //return back()->with(['msg' => 'Data Deleted Successfully.']);
    }
    
    
      
    public function destroy_receive($id)
    {
        // / payment_by ---> (cr) emp->dr
        // reveice_by ---> (dr) emp->cr
        try {
            DB::beginTransaction();

                $salaryPayment= SalaryPayment::whereId($id)->with(['payment', 'receive'])->first();
                $employeeLedger =LedgerSummary::where('ledger_id', $salaryPayment->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();

                $receive_by = LedgerSummary::where('ledger_id', $salaryPayment->receive_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($receive_by){
                    $receive_by->update(['debit' => $receive_by->debit - $salaryPayment->amount]);
                    $employeeLedger->update(['credit' => $employeeLedger->credit - $salaryPayment->amount]);
                }
                $payment_by=  LedgerSummary::where('ledger_id', $salaryPayment->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if($payment_by){
                    $payment_by->update(['credit' => $payment_by->credit - $salaryPayment->amount]);
                    $employeeLedger->update(['debit' => $employeeLedger->debit - $salaryPayment->amount]);
                }
                AccountLedgerTransaction::where('account_ledger__transaction_id', $salaryPayment->vo_no)->delete();

                $salaryPayment->delete();
                DB::commit();
            } catch (\Exception $ex) {
            DB::rollBack();

            return back()->with(['msg' => $ex->getMessage()]);
        }
        return back()->with(['msg' => 'Data Deleted Successfully.']);
    }
    
    public function print_salary_payment_recepet($vo_no)
    {
        $vo_no = $vo_no;
        return view('MBCorporationHome.salary_payment.print_salary_payment_recepet', compact('vo_no'));
    }
    
    public function print_salary_receive_recepet($vo_no)
    {
        $vo_no = $vo_no;
        return view('MBCorporationHome.salary_payment.print_salary_receive_recepet', compact('vo_no'));
    }
}
