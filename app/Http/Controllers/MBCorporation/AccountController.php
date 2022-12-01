<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\AccountLedger;
use App\AccountGroup;
use App\SaleMen;
use App\AccountLedgerTransaction;
use App\Companydetail;
use App\Helpers\Product;
use App\LedgerSummary;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\LogActivity;
use Session;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;

class AccountController extends Controller
{

	//...................start account_group_list.................
    public function account_group_list()
    {
        $account_group_list = AccountGroup::with('groupUnder')->get();
        return view('MBCorporationHome.accountinformation.account_group_list', compact('account_group_list'));
    }

    public function account_group_create()
    {
        $account_group_list =AccountGroup::get();
        return view('MBCorporationHome.accountinformation.account_group_create',compact('account_group_list'));
    }

    public function store_account_group(Request $request)
    {
        $request->validate([
            'account_group_name' => 'required|unique:account_groups',
            'account_group_nature' => 'required',
            ]);

        $data = $request->except('_token');
        $data['account_group_id'] = "ACG".rand(111111,999999).'-'.date('y');
        AccountGroup::create($data);
        (new LogActivity)->addToLog('AccountGroup Create.');

        return redirect()->to('account_group_list');

    }

    public function edit_account_group($account_group_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $account_group_list =AccountGroup::get();
        $oneAccountGroup = AccountGroup::where('account_group_id',$account_group_id)->with('groupUnder')->first();
        return view('MBCorporationHome.accountinformation.edit_account_group',compact('oneAccountGroup','account_group_list'));
    }

    public function update_account_group(Request $request,$account_group_id)
    {

        // dd($request->all(), $account_group_id);
        Validator::make($request->all(), [
            'account_group_nature' => [
            'required',
            Rule::unique('account_groups')->ignore($account_group_id),
            ],
            ]);

          AccountGroup::where('id',$account_group_id)->update([
            'account_group_name'=>$request->account_group_name,
            'account_group_nature'=>$request->account_group_nature,
            'account_group_under_id'=>$request->account_group_under_id??null,
            'description'=>$request->description??null,
         ]);
         (new LogActivity)->addToLog('AccountGroup updated.');
         return redirect()->to('account_group_list')->with('msg', 'Data Updated');
    }

    public function delete_account_group($account_group_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
       try {
            DB::beginTransaction();
            AccountGroup::where('account_group_id',$account_group_id)->delete();
            (new LogActivity)->addToLog('AccountGroup Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

    }

    //...................End account_group_list.................


    //...................start account_ledger_list.................
    public function account_ledger_list(Request $request)
    {
        $ledger_list = AccountLedger::with('accountGroupName');
        if($request->name) {
            $ledger_list = $ledger_list->where('account_name', 'LIKE', '%'.$request->name.'%');
        }
        if($request->type) {
            $ledger_list = $ledger_list->where('debit_credit', $request->type);
        }
        if($request->group) {
            $group = $request->group;
            $ledger_list = $ledger_list->whereIn('account_group_id', function($query)use($group) {
                return $query->select('id')->from('account_groups')->where('account_group_name', 'LIKE', $group.'%');
            });
        }
        $ledger_list = $ledger_list->paginate(10);
        return view('MBCorporationHome.accountinformation.account_lader_list', compact('ledger_list'));
    }

    public function account_ledger_create()
    {;
        $account_group_list =AccountGroup::get();
        return view('MBCorporationHome.accountinformation.account_lader_create',compact('account_group_list'));
    }

    public function store_account_ledger(Request $request)
    {
        $request->validate([
            'account_name' => 'required|unique:account_ledgers',
            'account_ledger_phone' => 'required',
            'debit_credit' => 'required',
            'account_ledger_opening_balance' => 'required',
            ]);


        try {
            Db::beginTransaction();
        //   return  (new Helper)::activeYear();
             $settingDate = Companydetail::with('financial_year')->first()->financial_year;

            $account_ledger_id = "AL".(new Product)->generateRandomString();
            $ledger =AccountLedger::create([
                        'account_ledger_id'     =>$account_ledger_id,
                        'payment'               =>$request->payment == 'on'?true:false,
                        'receive'               =>$request->receive == 'on'?true:false,
                        'account_name'          =>$request->account_name,
                        'account_group_id'      =>$request->account_group_id, //ledger group id
                        'account_ledger_phone'  =>$request->account_ledger_phone,
                        'account_ledger_email'  =>$request->account_ledger_email,
                        'account_ledger_opening_balance'=>$request->account_ledger_opening_balance,
                        'debit_credit'              =>$request->debit_credit,
                        'account_ledger_address'    =>$request->account_ledger_address,
                        'status'    =>$request->status,
                    ]);

            $account_ledger__transaction_id = "ALT".(new Product)->generateRandomString();
            if ($request->debit_credit < 2) {

                AccountLedgerTransaction::create([
                    'account_ledger_id'=>$account_ledger_id,
                    'ledger_id'=>$ledger->id,
                    'account_name'=> $account_ledger_id,
                    'account_ledger__transaction_id'=>$account_ledger__transaction_id,
                    'debit'=>$request->account_ledger_opening_balance,
                    'newbalcence'=>$request->account_ledger_opening_balance,
                    'newbalcence_type'=>'1',
                ]);

                // $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->where('financial_date', (new Helper)::activeYear())->first();
                $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->first();
                    if($summary){
                        $summary->update(['debit' => $request->account_ledger_opening_balance + $summary->debit ]);
                    }else{
                        LedgerSummary::updateOrCreate(['ledger_id' =>$ledger->id],
                        ['debit' => $request->account_ledger_opening_balance, 'financial_date' => $settingDate->financial_year_from."/".$settingDate->financial_year_to]);
                    }

            }else{
                AccountLedgerTransaction::create([
                    'account_ledger_id'=>$account_ledger_id,
                    'ledger_id'=>$ledger->id,
                    'account_name'=> $account_ledger_id,
                    'account_ledger__transaction_id'=>$account_ledger__transaction_id,
                    'credit'=>$request->account_ledger_opening_balance,
                    'newbalcence'=>$request->account_ledger_opening_balance,
                    'newbalcence_type'=>'2',
                ]);
                $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->first();
                // $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->where('financial_date', (new Helper)::activeYear())->first();
                if($summary){
                    $summary->update(['credit' => $request->account_ledger_opening_balance + $summary->credit ]);
                }else{
                    LedgerSummary::updateOrCreate(['ledger_id' => $ledger->id , ],

                    ['credit' => $request->account_ledger_opening_balance, 'financial_date' => $settingDate->financial_year_from."/".$settingDate->financial_year_to]);
                }
            }
                (new LogActivity)->addToLog('AccountLedger Created.');

            Db::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect()->to('account_ledger_create');

    }


     public function edit_account_ledger($account_ledger_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $account_group_list = AccountGroup::get();
        $oneaccount_ledger = AccountLedger::where('account_ledger_id',$account_ledger_id)->get();

        return view('MBCorporationHome.accountinformation.edit_account_ledger',compact('account_group_list','oneaccount_ledger'));
    }

    public function update_account_ledger(Request $request,$account_ledger_id)
    {

        $request->validate([
            'account_name' => 'required',
            'account_ledger_phone' => 'required',
            ]);

        try {
            DB::beginTransaction();
            $ledger=AccountLedger::where('account_ledger_id',$account_ledger_id )->first();

            $accountLedgerTransaction = AccountLedgerTransaction::where('account_ledger_id',$account_ledger_id)->first();

            if ($request->debit_credit == 1) { //debit
                    $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->first();
                    // $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->where('financial_date', (new Helper)::activeYear())->first();
                    if($ledger->debit_credit == 1) {
                        $summary->update(['debit' => $summary->debit - $request->account_ledger_opening_balance_old]);
                    } elseif($ledger->debit_credit == 2) {
                        $summary->update(['credit' => $summary->credit - $request->account_ledger_opening_balance_old]);
                    }
                    
                    $summary->update(['debit' => $request->account_ledger_opening_balance + $summary->debit]);
                    
                    $accountLedgerTransaction->update([
                        'debit'=>$request->account_ledger_opening_balance,
                        'credit'=>0,
                        'newbalcence'=>$request->account_ledger_opening_balance,
                    ]);
            }else{ // credit
                $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->first();
                // $summary = LedgerSummary::where('ledger_id' ,$ledger->id)->where('financial_date', (new Helper)::activeYear())->first();

                if($ledger->debit_credit == 1) {
                    $summary->update(['debit' => $summary->debit - $request->account_ledger_opening_balance_old]);
                } elseif($ledger->debit_credit == 2) {
                    $summary->update(['credit' => $summary->credit - $request->account_ledger_opening_balance_old]);
                }
                
                $summary->update(['credit' => $request->account_ledger_opening_balance + $summary->credit]);
                    
                $accountLedgerTransaction->update([
                        'credit'=>$request->account_ledger_opening_balance,
                        'debit'=>0,
                        'newbalcence'=>$request->account_ledger_opening_balance,
                    ]);
            }
            
            // update summary grand total 
            $summary->update([
                'grand_total' => $summary->debit - $summary->credit,
            ]);

            $ledger->update([
                'account_name'          =>$request->account_name,
                'payment'               =>$request->payment == 'on'?true:false,
                'receive'               =>$request->receive == 'on'?true:false,
                'account_group_id'      =>$request->account_group_id,
                'account_ledger_phone'  =>$request->account_ledger_phone,
                'account_ledger_email'          =>$request->account_ledger_email,
                'account_ledger_opening_balance'=>$request->account_ledger_opening_balance,
                'debit_credit'              =>$request->debit_credit,
                'status'              =>$request->status,
                'account_ledger_address'   =>$request->account_ledger_address,
            ]);
            (new LogActivity)->addToLog('AccountLedger Updated.');

            DB::commit();
        } catch (\Exception $ex) {
            Db::rollBack();
            return $ex->getMessage();
        }

        return redirect()->to('account_ledger_list');
    }

    public function delete_account_ledger($id)
    {
    $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        
        try {
            DB::beginTransaction();
            AccountLedger::where('id',$id)->delete();
            (new LogActivity)->addToLog('AccountLedger Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);

    }

    //...................End account_ledger_list.................

    //...................start selasman_list.................
    public function selasman_list()
    {
        $SaleMan_list =SaleMen::get();
        return view('MBCorporationHome.accountinformation.salesmen_list', compact('SaleMan_list'));
    }

    public function selasman_create()
    {
        return view('MBCorporationHome.accountinformation.salesmen_create');
    }


    public function store_selasman(Request $request)
    {

        $validatedData = $request->validate([
            'salesman_name' => 'required|unique:sale_mens',
            'phone' => 'required',
            ]);

        $salesman_id = "SLM".(new Product)->generateRandomString();
        $v= SaleMen::create([
            'salesman_id'=>$salesman_id,
            'salesman_name'=>$request->salesman_name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'address'=>$request->address,
        ]);
        (new LogActivity)->addToLog('Sale Man Created.');

        // dd($v);
        $SaleMan_list =SaleMen::get();
        return view('MBCorporationHome.accountinformation.salesmen_list', compact('SaleMan_list'));
    }

     public function edit_SaleMan($salesman_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        $one_SaleMan = SaleMen::where('salesman_id',$salesman_id)->get();
        return view('MBCorporationHome.accountinformation.edit_salesmen',compact('one_SaleMan'));
    }

    public function update_SaleMan(Request $request,$salesman_id)
    {
            $validatedData = $request->validate([
            'salesman_name' => 'required',
            'phone' => 'required',
            ]);

            SaleMen::where('salesman_id',$salesman_id)->Update([
                'salesman_name'=>$request->salesman_name,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'address'=>$request->address,
            ]);
        (new LogActivity)->addToLog('Sale Man Updated.');

        $SaleMan_list =SaleMen::get();
        return view('MBCorporationHome.accountinformation.salesmen_list', compact('SaleMan_list'));
    }

    public function delete_SaleMan($SaleMan_id)
    {
        $p=(new Helper)::upserpermission(\Route::getFacadeRoot()->current()->uri());
        if($p){Session::flash('warning','Access Denied!');return redirect()->back();}
        try {
            DB::beginTransaction();
            SaleMen::where('salesman_id',$SaleMan_id)->delete();
            (new LogActivity)->addToLog('SaleMen Deleted.');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['mes' =>  $ex->getMessage(), 'status' => false]);
        }
        return response()->json(['mes' => "Successfully Deleted", 'status' => true]);
    }
    //...................End selasman_list.................


    public function chat_of_account()
    {
        return view('MBCorporationHome.accountinformation.chat_of_account');
    }



}
