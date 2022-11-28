<?php

namespace App\Http\Controllers\MBCorporation;

use App\AccountLedgerTransaction;
use App\Department;
use App\Designation;
use App\Employee;
use App\Helpers\Helper;
use App\Helpers\LogActivity;
use App\Helpers\Product;
use App\Shift;
use App\Http\Controllers\Controller;
use App\LedgerSummary;
use App\SalaryDetails;
use App\SalaryPayment;
use App\SaleDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class EmployeeController extends Controller
{

    public function index()
    {
        $mes = "";
        $employees =  Employee::active()->get();
        return view('MBCorporationHome.employee.index', compact('employees', 'mes'));
    }

    public function create()
    {
        $departments = Department::get(['id', 'name']);
        $designations = Designation::get(['id', 'name']);
        $employees = Employee::get(['id', 'name']);
        $shifts      = Shift::get(['id', 'name']);
        $mes = "";
        return view('MBCorporationHome.employee.create', compact('departments', 'employees', 'shifts', 'mes', 'designations'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:employees|max:25|min:3',
            'mobile' => 'required|unique:employees|max:20|min:11',
        ]);

        try {

            DB::beginTransaction();
            $data = $request->except('_token');
            $account_ledger_id = "AL" . (new Product)->generateRandomString();
            $data['account_ledger_id'] = $account_ledger_id;
            if(Employee::first()){
                $ledger = Employee::create($data);
            }else{
                $data['id'] = 990000;
                $ledger = Employee::create($data);
            }
            if ($request->advance_amount>0) {
                $account_ledger__transaction_id = "ALT" . (new Product)->generateRandomString();
                AccountLedgerTransaction::create([
                    'account_ledger_id' => $account_ledger_id,
                    'ledger_id' => $ledger->id,
                    'account_name' => $account_ledger_id,
                    'account_ledger__transaction_id' => $account_ledger__transaction_id,
                    'debit'     => $request->advance_amount,
                    'newbalcence'  => $request->advance_amount,
                    'newbalcence_type' => '1',
                ]);

                $summary = LedgerSummary::where('ledger_id', $ledger->id)
                ->where('financial_date', (new Helper)::activeYear())
                ->first();
                if ($summary) {
                    $summary->update(['debit' => $request->advance_amount + $summary->debit]);
                } else {
                    LedgerSummary::updateOrCreate(['ledger_id' => $ledger->id,'financial_date' => (new Helper)::activeYear()], [
                        'debit' => $request->advance_amount
                    ]);
                }
            }
            (new LogActivity)->addToLog('Employee Created.');

            $mes = "Successfully Add Employee";

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            $mes = $ex->getMessage();
            dd( $ex->getMessage());
        }
        return redirect()->to('employee/index')->with(['mes' => $mes]);
    }
    public function edit($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $departments = Department::get(['id', 'name']);
        $designations = Designation::get(['id', 'name']);
        $employees = Employee::get(['id', 'name']);
        $shifts      = Shift::get(['id', 'name']);
        $mes = "";
        $employee = Employee::find($id);

        return view('MBCorporationHome.employee.edit', compact('employee', 'departments', 'employees', 'shifts', 'mes', 'designations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:25|min:3|unique:employees,name,' . $id,
            'mobile' => 'required||max:25|min:11|unique:employees,mobile,' . $id
        ]);

        $ledger = Employee::where('id', $id)->first();
        if ($request->advance_amount) {
            $summary = LedgerSummary::where('ledger_id', $ledger->id)
            ->where('financial_date', (new Helper)::activeYear())
            ->first();
            if ($summary) {
                $summary->update(['debit' => $request->advance_amount + $summary->debit - $ledger->advance_amount]);
            } else {
                LedgerSummary::updateOrCreate(['ledger_id' => $ledger->id,'financial_date' => (new Helper)::activeYear()], [
                    'debit' => $request->advance_amount
                ]);
            }
            $accountLedgerTransaction = AccountLedgerTransaction::where('account_ledger_id', $ledger->account_ledger_id)->first();
            if ($accountLedgerTransaction) {
                $accountLedgerTransaction->update([
                    'debit' => $request->advance_amount,
                    'newbalcence' => $request->advance_amount,
                ]);
            } else {
                $account_ledger__transaction_id = "ALT" . (new Product)->generateRandomString();
                AccountLedgerTransaction::create([
                    'account_ledger_id'     => $ledger->account_ledger_id,
                    'ledger_id'             => $ledger->id,
                    'account_name'          => $ledger->account_ledger_id,
                    'account_ledger__transaction_id' => $account_ledger__transaction_id,
                    'debit'                 => $request->advance_amount,
                    'newbalcence'           => $request->advance_amount,
                    'newbalcence_type'      => '1',
                ]);
            }
        }

        $ledger->update($request->except('_token'));
        (new LogActivity)->addToLog('Employee Updated.');

        $mes = "";
        return redirect()->to('employee/index')->with(['mes' => $mes]);
    }

    public function delete($id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            $ledger = Employee::with('journals')->where('id', $id)->first();

            $accountLedgerTransaction = AccountLedgerTransaction::where('account_ledger_id', $ledger->account_ledger_id)->get();
            $salaryDetails = SalaryDetails::where('employee_id',$id )->first();
            $SalaryPayment = SalaryPayment::where('employee_id',$id )->first();
            if ($salaryDetails|| $SalaryPayment || count($ledger->journals()->get())> 0) {
                return redirect()->to('employee/index')->with(['message' => 'This Table data use another table!']);
            }
            if($accountLedgerTransaction){
                foreach ($accountLedgerTransaction as $key => $transaction) {
                    $transaction->delete();
                }
            }
            $summary = LedgerSummary::where('ledger_id', $ledger->id)
            ->where('financial_date', (new Helper)::activeYear())
            ->delete();

            $ledger->delete();
            $mes = "Successfully Deleted";
            (new LogActivity)->addToLog('Employee Deleted.');

            DB::commit();
        } catch (\Exception $ex) {
            $mes = $ex->getMessage();
            DB::rollBack();
        }
        return redirect()->to('employee/index')->with(['message' => $mes]);
    }

    public function status($id)
    {
        $employee = Employee::where('id', $id)->first();


        if ($employee->status) {
            $employee->update(['status' => false]);
        } else {
            $employee->update(['status' => true]);
        }
        $mes = "Successfully updated";

        return redirect()->to('employee/index')->with(['mes' => $mes]);
    }
}
