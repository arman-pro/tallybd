<?php

namespace App\Http\Controllers\MBCorporation;

use App\AccountGroup;
use App\AccountLedger;
use App\AccountLedgerTransaction;
use App\Department;
use App\Designation;
use App\Employee;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\LedgerSummary;
use App\Salary;
use App\SalaryDetails;
use App\SaleDetails;
use App\SalaryPayment;
use App\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Datatables;

class SalaryController extends Controller
{

    public function datatables()
    {
        $salaries = Salary::with(['ledger', 'createdBy'])->orderBy('id', 'desc');
        return Datatables()->eloquent($salaries)
        ->addIndexColumn()
        ->editColumn("salary_date", function(Salary $salary) {
            return "<a role='button' class='copy_text' href='javascript:void(0)' data-text='".$salary->salary_date."'>".date('d-m-y', strtotime($salary->salary_date))."</a>";
        })
        ->editColumn("generated_date", function(Salary $salary) {
            return "<a role='button' class='copy_text' href='javascript:void(0)' data-text='".$salary->date."'>".date('d-m-y', strtotime($salary->date))."</a>";
        })
        ->editColumn("salary", function(Salary $salary) {
            return number_format($salary->total_amount, 2);
        })
        ->editColumn("payment_by", function(Salary $salary) {
            return optional($salary->ledger)->account_name ?? "";
        })
        ->editColumn("created_by", function(Salary $salary) {
            return optional($salary->createdBy)->name ?? "";
        })
        ->addColumn('action', function(Salary $salary) {
            return make_action_btn([
                '<a href="'.route("salary.print", ['id' => $salary->id]).'" class="dropdown-item"><i class="far fa-print"></i> Print</a>',
                '<a href="'.route("salary.edit",['id' => $salary->id]).'" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>',
                '<a href="javascript:void(0)" data-id="'.$salary->id.'" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>',
            ]);
        })
        ->rawColumns(['action', 'salary_date', "generated_date"])
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
        return view('MBCorporationHome.salary.index');
    }
    
    public function print($id)
    {
        $salary = Salary::with(['details', 'shift', 'department', 'designation'])->find($id);
        return view('MBCorporationHome.salary.print', compact('salary'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designations = Designation::get(['id', 'name']);
        $departments = Department::get(['id', 'name']);
        $shifts     = Shift::get(['id', 'name']);
        $ledgers=AccountLedger::where('payment', 1)->get(['id', 'account_name']);
        $mes = "";
        $employees = [];
        return view('MBCorporationHome.salary.create', compact('shifts', 'designations', 'employees', 'ledgers', 'departments', 'mes'));
    }

    public function searchSalary(Request $request)
    {
        $employee = Employee::whereId($request->employee_id)->first();
        if ($employee) {
            $salary = $employee->salary;
        } else {
            $salary = null;
        }
        return response()->json(['salary' => $salary]);
    }

    public function getEmployee(Request $request)
    {
        $query = Employee::where('status', true);
        if ($request->designation_id) {
            $query = $query->where('designation_id', $request->designation_id);
        }
        if ($request->department_id) {
            $query = $query->where('department_id', $request->department_id);
        }
        if ($request->shift_id) {
            $query = $query->where('shift_id', $request->shift_id);
        }
        $designations   = Designation::get(['id', 'name']);
        $departments    = Department::get(['id', 'name']);
        $shifts         = Shift::get(['id', 'name']);
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
        $employees      = $query->get();

        return view('MBCorporationHome.salary.create', compact('shifts', 'designations', 'employees', 'ledgers', 'departments', 'mes'));
    }
    public function reportSalary()
    {
        return view('MBCorporationHome.salary.report');
    }
    public function reportEmployee(Request $request)
    {
        if($request->ledger_id){
            $employee =Employee::where('id', $request->ledger_id)->first();
            return view('MBCorporationHome.salary.salary_ledger_report', compact('employee'));
        }else{
            $employees =Employee::get();
            $table='';
            $totaldr=0;
            $totalcr=0;
            foreach ($employees as $keys => $employee) { 

                
                $newBalance = 0;
                
                $opening = AccountLedgerTransaction::where('ledger_id',$employee->id)->where('date', '<=' ,request()->from_date
                                )->get()->unique('account_ledger__transaction_id');
                                
                
                                
                $salaryDetails = SalaryDetails::where('employee_id', $employee->id)->where('salary_date', '<=', $request->to_date)->get();
                $salaryDetails = $salaryDetails->map(function($value,$i){
                    return [
                        'date'=>date('y-m-d', strtotime($value->salary_date)),
                        'type'=>'Generate',
                        'dr'=>0,
                        'cr'=>$value->salary,
                    ];
                });

                $salaryPayment = SalaryPayment::where('employee_id', $employee->id)->where('date', '<=', $request->to_date)->get();

                $salaryPayment = $salaryPayment->map(function($value,$i){
                    if ($value->payment_by) {
                        return [
                                'date'=>date('y-m-d', strtotime($value->date)),

                            // 'date'=>$value->date,
                            'type'=>'Payment',
                            'dr'=>$value->amount,
                            'cr'=> 0,
                        ];
                    }else{
                        return [
                            'date'=>date('y-m-d', strtotime($value->date)),

                            'type'=>'Receive',
                            'dr'=> 0,
                            'cr'=>$value->amount,
                        ];
                    }

                });
                $account_tran=[];
                
                foreach ($salaryDetails as $key => $Details) {
                    array_push($account_tran, $Details);
                }
                foreach ($salaryPayment as $key => $Payment) {
                    array_push($account_tran, $Payment);
                }
                /*if(count($salaryDetails) > 0 && count($salaryPayment) > 0 ){
                    foreach ($salaryDetails as $key => $Details) {
                        array_push($account_tran, $Details);
                        array_push($account_tran, $salaryPayment[$key]);
                    }
                }elseif (count($salaryDetails) > 0) {
                    foreach ($salaryDetails as $key => $Details) {
                        array_push($account_tran, $Details);
                    }
                }elseif (count($salaryPayment) > 0) {
                    foreach ($salaryPayment as $key => $Payment) {
                        array_push($account_tran, $Payment);
                    }
                }*/
                
                //$account_tran = collect($account_tran)->sortBy('date')->all();
                
                $dr = 0;
                $cr = 0;
                foreach($account_tran as $key=>$account_tran_row){
                
                    $dr+=$account_tran_row['dr'];
                    $cr+=$account_tran_row['cr'];
                  
                    $newBalance = $dr- $cr;
                     
                }
                
                $crBalance = 0;
                $drBalance = 0;
                
                if(($dr- $cr) < 0){
                    $crBalance = $dr- $cr;
                }else{
                    $drBalance = $dr- $cr;
                }
                
                 // opening balance add
                if($opening){
                    $drBalance += $opening->sum('debit');
                    $crBalance += $opening->sum('credit');
                }
                
                    $table.='<tr style="font-size:14px">';
                    $table.='<td>'. ($keys+1) .'</td>';
                    $table.='<td>'. $employee->name.'</td>';
                    $table.='<td>'. $employee->mobile ?? "N/A" .'</td>';
                    $table.='<td  style="text-align: right">'.number_format($drBalance, 2).'</td>';
                    $table.='<td  style="text-align: Center">'.number_format($crBalance, 2).'</td>';
                    
                    $table.='</tr>';
                
                $totaldr+=$drBalance;
                $totalcr+=$crBalance;
            }
            $table.='<tr>';
            $table.='<td colspan="3" style="text-align: right"><strong>Total</strong></td>';
            $table.='<td style="text-align: right">'.number_format($totaldr, 2) .'</td>';
            $table.='<td style="text-align: Center">'.number_format($totalcr, 2) .'</td>';
            $table.='</tr>';

            return view('MBCorporationHome.salary.allsalary_ledger_report', compact('employees', 'table'));
        }
        
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
            'salary_date' => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
            'employee_id.*'   => 'required',
            'vo_no'         => 'required|unique:salaries',
            'payment_by'     => 'required',
            'salary.*'        => 'required|numeric|gt:0',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['total_amount'] = array_sum($request->salary);
            unset($data['employee_salary']);
            unset($data['employee_id']);
            unset($data['day']);
            unset($data['salary']);

            $salary = Salary::create($data);

            if ($request->payment_by) {
                $summary = LedgerSummary::where('ledger_id', $request->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                
                if ($summary) {
                    $summary->update(['debit' =>  $data['total_amount'] + $summary->debit]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by,'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $data['total_amount']
                    ]);
                }

                $account_ledger = AccountLedger::where('id', $request->payment_by)->first();
                $accountLedgerTransaction = new AccountLedgerTransaction();
                $accountLedgerTransaction->ledger_id = $account_ledger->id;
                $accountLedgerTransaction->account_ledger_id = $account_ledger->account_ledger_id;
                $accountLedgerTransaction->account_name = $account_ledger->account_name;
                $accountLedgerTransaction->account_ledger__transaction_id = $request->vo_no;
                $accountLedgerTransaction->debit = $data['total_amount'];
                $accountLedgerTransaction->date = $request->salary_date;
                $accountLedgerTransaction->save();
                // AccountLedgerTransaction::create([
                //     'ledger_id' => $account_ledger->id,
                //     'account_ledger_id' => $account_ledger->account_ledger_id,
                //     'account_name' => $account_ledger->account_name,
                //     'account_ledger__transaction_id' => $request->vo_no,
                //     'debit' =>  $data['total_amount'],
                //     'date' => $request->salary_date,
                // ]);
            }
            if ($request->employee_id) {
                foreach ($request->employee_id as $key => $employee_id) {
                    $summary = LedgerSummary::where('ledger_id', $employee_id)
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();
                    if ($summary) {
                        $summary->update(['credit' => $request->salary[$key] + $summary->credit]);
                    } else {
                        LedgerSummary::updateOrCreate(['ledger_id' => $employee_id,'financial_date' => (new Helper)::activeYear()], [
                            'credit' => $request->salary[$key]
                        ]);
                    }
                    $detailsData = $data;
                    unset($detailsData['employee_salary']);
                    $detailsData['employee_id'] = $employee_id;
                    $detailsData['day'] = $request->day[$key];
                    $detailsData['salary'] = $request->salary[$key];
                    $v= $salary->details()->create($detailsData);
                }
            }
            (new LogActivity)->addToLog('Salary Generate Created.');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        $mes = "Successfully Added Salary";

        return redirect()->to('salary/index')->with(['mes' => $mes]);
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
        $salary = Salary::with('details')->find($id);
        $designations = Designation::get(['id', 'name']);
        $departments = Department::get(['id', 'name']);
        $shifts     = Shift::get(['id', 'name']);
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
        return view('MBCorporationHome.salary.edit', compact('designations', 'shifts',
        'departments', 'salary', 'shifts', 'mes','ledgers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Helper $helper, $id)
    {
        //dd($request);
        $request->validate([
            'salary_date'     => 'required|before_or_equal:'.$helper::companySetting()->financial_year_to.'|after_or_equal:'.$helper::companySetting()->financial_year_from,
            'employee_id.*'   => 'required',
            'vo_no'           => 'required|unique:salaries,vo_no,' .$id,
            'payment_by'      => 'required',
            'salary.*'        => 'required|numeric|gt:0',
            'salary_date'     => 'required',
            // 'salary_date'     => 'required|unique:salaries,salary_date,' . $id,
        ]);


        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['total_amount'] = array_sum($request->salary);
            unset($data['_token']);
            unset($data['employee_salary']);
            unset($data['employee_id']);
            unset($data['day']);
            unset($data['salary']);
            $salary = Salary::with('details')->find($id);
            if ($salary->payment_by == $request->payment_by) {
                //echo $request->payment_by;
                AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->where('debit', '>', 0)
                ->first()
                ->update(['debit' => $data['total_amount'], 'date' => $request->salary_date]);
                
                $summary = LedgerSummary::where('ledger_id', $request->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                
                //dd($summary);
                if ($summary) {
                    //$summary->update(['debit' => $data['total_amount'] + $summary->credit - $salary->total_amount]);
                    $summary->update(['debit' => abs($summary->debit - $salary->total_amount)]);
                    $summary->update(['debit' => abs($summary->debit + $data['total_amount'])]);
                }

            }else{
                AccountLedgerTransaction::where('account_ledger__transaction_id', $request->vo_no)->where('debit', '>', 0)->delete();
                $old_summary = LedgerSummary::where('ledger_id', $salary->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($old_summary) {
                    $old_summary->update(['debit' => abs($old_summary->debit -  $salary->total_amount)]);
                }
                $account_ledger = AccountLedger::where('id', $request->payment_by)->first();
                AccountLedgerTransaction::create([
                    'ledger_id' => $account_ledger->id,
                    'account_ledger_id' => $account_ledger->account_ledger_id,
                    'account_name' => $account_ledger->account_name,
                    'account_ledger__transaction_id' => $request->vo_no,
                    'debit' =>  $data['total_amount'],
                    'date' => $request->salary_date,
                ]);

                $summary = LedgerSummary::where('ledger_id', $request->payment_by)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($summary) {
                    $summary->update(['debit' =>  $data['total_amount']+ $summary->debit]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $request->payment_by, 'financial_date' => (new Helper)::activeYear()], [
                        'debit' =>  $data['total_amount']
                    ]);
                }
            }


            $currentEmployee = $request->employee_id;

            $oldEmployee=[];
            foreach ($salary->details->pluck('employee_id') as $key => $value) {
                array_push( $oldEmployee, strval($value));
            }

            $deleteEmployees = array_diff($currentEmployee, $oldEmployee);
            if($deleteEmployees){
                for ($i=0; $i <count($deleteEmployees) ; $i++) {
                    $deletedSalary =SalaryDetails::where('salary_id', $id)->where('employee_id', $deleteEmployees[$i])->first();
                    $summary = LedgerSummary::where('ledger_id', $deleteEmployees[$i])
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();
                    if ($summary) {
                        $summary->update(['credit' => abs($summary->credit - $deletedSalary->salary) ]);
                    }
                    $deletedSalary->delete();
                }
            }
            if ($currentEmployee) {
                for ($i=0; $i <count($currentEmployee) ; $i++) {
                    $detailsData = $request->alL();
                    unset($detailsData['employee_salary']);
                    $detailsData['employee_id'] = $currentEmployee[$i];
                    $detailsData['day']     = $request->day[$i];
                    $detailsData['salary']  = $request->salary[$i];
                    $salaryDetails = SalaryDetails::where('salary_id', $id)->where('employee_id', $currentEmployee[$i])->first();
                    $summary = LedgerSummary::where('ledger_id', $currentEmployee[$i])
                    ->where('financial_date', (new Helper)::activeYear())
                    ->first();
                    if ($summary) {
                        $summary->update(['credit' => abs($request->salary[$i] + $summary->credit - $salaryDetails->salary)]);
                    } else {
                        LedgerSummary::updateOrCreate(['ledger_id' =>$currentEmployee[$i],'financial_date' => (new Helper)::activeYear()], [
                            'credit' => $request->salary[$i]
                        ]);
                    }

                    $salaryDetails->update($detailsData);
                }
            }
            $salary->update($data);
            (new LogActivity)->addToLog('Salary Generate Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            $mes=$ex->getMessage();
        }


        $mes = "Successfully Update Salary";
        return redirect()->to('salary/index')->with(['mes' => $mes]);
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
        // return AccountLedger::with('summary')->find(125);
        try {
            DB::beginTransaction();
            $salary =  Salary::with('details')->find($id);
            AccountLedgerTransaction::where('account_ledger__transaction_id', $salary->vo_no)->delete();
            $payment_summary = LedgerSummary::where('ledger_id', $salary->payment_by)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            // dd(floatval($payment_summary->credit) -  floatval($salary->salary))
            if ($payment_summary) {
                $payment_summary->update(['debit' => abs($payment_summary->debit -  $salary->total_amount)]);
            }

            foreach ($salary->details()->get() as $key => $deletedSalary) {
                $summary = LedgerSummary::where('ledger_id', $deletedSalary->employee_id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($summary) {
                    $summary->update(['credit' => abs($summary->credit - $deletedSalary->salary) ]);
                }else{
                    dd('something is wrong!');
                }
                $deletedSalary->delete();
            }

            $salary->delete();
            (new LogActivity)->addToLog('Salary Generate Deleted.');
            $mes = "Successfully Deleted Salary";
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            $mes=$ex->getMessage();
        }
        //return redirect()->to('salary/index')->with(['mes' => $mes]);
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
    }
}
