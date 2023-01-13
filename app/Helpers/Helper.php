<?php
namespace App\Helpers;

use App\AccountGroup;
use App\Companydetail;
use Session;
use Auth;
use DB;
use App\AccountLedgerTransaction;
use App\FinancialYear;
use App\StockHistory;
use App\PurchasesAddList;
use App\Item;

class Helper
{
    
    public static function day_get_profit_loos($date) {
        $from_date  = $date['from_date'];
        $to_date    = $date['to_date'];
        
        $profit = 0;
        $loos = 0;

        
        $present_stock = StockHistory::whereBetween('date', [$from_date, $to_date])
        ->whereIn('stockable_type', [
            'App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 
            'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 
            'App\WorkingOrder','App\Production'
        ])->get();
        
        $expenses = $income = 0;
        
        //---------- Expenses-------------
        $expense_transaction = DB::table('account_ledger_transactions')
        ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
        ->whereIn('ledger_id', function($query){
            $query->from('account_ledgers')->select('id')->whereIn('account_group_id', function($q){
                $q->from('account_groups')->select('id')->where('account_group_nature','Expenses')->get();
            })->get();
        })->whereBetween('date', [$from_date, $to_date])->first();
        
        $expenses = $expense_transaction->total_debit - $expense_transaction->total_credit;
        
        //---------- Income-------------
        $expense_transaction = DB::table('account_ledger_transactions')
        ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
        ->whereIn('ledger_id', function($query){
            $query->from('account_ledgers')->select('id')->whereIn('account_group_id', function($q){
                $q->from('account_groups')->select('id')->where('account_group_nature','Income')->get();
            })->get();
        })->whereBetween('date', [$from_date, $to_date])->first();
        
        $income = $expense_transaction->total_credit - $expense_transaction->total_debit;
        
        $totalPurchase = PurchasesAddList::whereBetween('date',[$from_date, $to_date])
                        ->orderBy('date')->get()->sum('grand_total');
                      
        $totalReturnPurchase = StockHistory::whereBetween('date', [$from_date, $to_date])
                            ->whereIn('stockable_type', ['App\PurchasesReturnAddList'])
                            ->get(['total_qty', 'total_average_price'])
                            ->sum('total_average_price');
                              
        $totalSale = StockHistory::whereBetween('date', [$from_date, $to_date])
                    ->whereIn('stockable_type', ['App\SalesAddList'])
                    ->get(['total_qty', 'total_average_price'])
                    ->sum('total_average_price');
                   
        $totalReturnSale = StockHistory::whereBetween('date', [$from_date, $to_date])
                    ->whereIn('stockable_type', ['App\SalesReturnAddList'])
                    ->get(['total_qty', 'total_average_price'])->sum('total_average_price');
                 
        $item = Item::get();
        $opening_total_pur_price = 0;
        foreach($item as $i=>$item_row){
            $opening= StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
            ->where('item_id', $item_row->id)
            ->where('date', '<', $from_date)->get();
            $totalCount = 0;
            $total_in = 0;
            $averagePrice = 0;
            $totalPrice = 0;
            $totalPur_Price = 0;
            foreach ($opening as $key => $history) {
                $total_in += $history->in_qty;
                if($history->in_qty >0){
                    $totalPur_Price += $history->total_average_price;
                }
                $totalCount += $history->total_qty;
                $totalPrice += $history->total_average_price;
            }

            if($totalPur_Price>0 && $total_in>0){
                $averagePrice =  ($totalPur_Price / $total_in);
            }
            
            $opening_total_pur_price += $averagePrice*$totalCount;
        }
    
        $present_total_pur_price = 0;
        
        foreach($item as $i=>$item_row){
            $present_stock= StockHistory::whereIn('stockable_type', ['App\Item','App\PurchasesAddList', 'App\PurchasesReturnAddList', 'App\SalesAddList','App\SalesReturnAddList', 'App\StockAdjustment', 'App\WorkingOrder','App\Production'])
            ->where('item_id', $item_row->id)
            // this is change by arman
            ->where('date', '<=', $to_date)->get();
           // ->where('date', '<', $toDate)->get();
            $totalCount = 0;
            $total_in = 0;
            $averagePrice = 0;
            $totalPrice = 0;
            $totalPur_Price = 0;
            foreach ($present_stock as $key => $history) {
                $total_in += $history->in_qty;
                if($history->in_qty >0){
                    $totalPur_Price += $history->total_average_price;
                }
                $totalCount += $history->total_qty;
                $totalPrice += $history->total_average_price;
            }

            if($totalPur_Price>0 && $total_in>0){
                $averagePrice =  ($totalPur_Price / $total_in);
            }
            
            $present_total_pur_price += $averagePrice*$totalCount;
        }

       $leftSide = 0;
        $rightSide= 0;
        if($opening_total_pur_price > 0){
            $leftSide = $opening_total_pur_price+$totalPurchase+$totalReturnSale+$expenses;

        }else{
            $leftSide = $totalPurchase+$totalReturnSale+$expenses;
        }
        if($present_total_pur_price >0){
            $rightSide += $present_total_pur_price;
            $rightSide+= abs($totalSale);
            $rightSide+= ($totalReturnPurchase*-1);
            $rightSide+= abs($income);

        }else{
            $rightSide+= abs($totalSale);
            $rightSide+= ($totalReturnPurchase*-1);
            $rightSide+= abs($income);
        }
        
        if($leftSide > $rightSide){
            $profit = 0;
            $loss = number_format($leftSide - $rightSide, 2) ?? 0;
        }else {
            $profit = number_format($rightSide - $leftSide , 2) ?? 0;
            $loss = 0;
        }
        
        return [
          'profit' => $profit,
          'loos' => $loss,
        ];
    }

    public static function IDGenerator($model, $trow, $length = 6, $prefix){
        $data = $model::orderBy('id','desc')->first();

        if(!$data){
            $og_length = $length;
            $last_number = '1';
        }else{
            $code = substr($data->$trow, strlen($prefix)+1);
            $code =  substr($code, 0, -3);
            $actial_last_number = ($code/1)*1;
            $increment_last_number = ((int)$actial_last_number)+1;
            $last_number_length = strlen($increment_last_number);
            $og_length = $length - $last_number_length;
            $last_number = $increment_last_number;
        }
        $zeros = "";
        for($i=0;$i<$og_length;$i++){
            $zeros.="0";
        }
        return $prefix.'-'.$zeros.$last_number.'-'.date('y');
    }
    
    public static function get_financial_year_from() {
        $financial_year = FinancialYear::where('status', 1)->first();
        if($financial_year)
            return $financial_year->financial_year_from;
        else 
            return null;
    }



    public static function activeYear()
    {
        $companydetail= Companydetail::with('financial_year')->first();
        return optional($companydetail->financial_year)->financial_year_from.'/'.optional($companydetail->financial_year)->financial_year_to;
    }

    public static function companySetting()
    {
        return  Companydetail::with('financial_year')->first()->financial_year;
    }


    public static function headAccountTransaction_date($nature, $date=null)
    {
          $settingDate =Helper::activeYear();

        return AccountGroup::where('account_group_nature', $nature)
        ->whereNull('account_group_under_id')
        ->with(['childrenCategories.accountLedgers.summary' => function($summary) use ($settingDate){
            // $summary->where('financial_date', $settingDate);
        }])
        ->with(['accountLedgers.summary' => function($summary) use ($settingDate){
            // $summary->where('financial_date', $settingDate);
        }])
        ->get()
        ->map(function($expenseItem, $key){
            $amount = 0;
            if($expenseItem->accountLedgers){
                $amount +=  (new Helper)->ledgerCal($expenseItem->accountLedgers, $amount);
            }
            if($expenseItem->childrenCategories){
                $amount =  (new Helper)->recursiveFunction($expenseItem->childrenCategories, $amount);
            }
            return [
                'name' => $expenseItem->account_group_name,
                'amount' => $amount
            ];
        })
        ;

    }
    
    public static function date_wise_head_account_summary($nature, $date) {
        
        $account_groups = AccountGroup::where('account_group_nature', $nature)
                ->with(['childrenCategories'])
                ->get();
            $total_amount = 0;
            foreach($account_groups as $account_group) {
                if($account_group->childrenCategories) {
                    foreach($account_group->childrenCategories as $account_group_children) {
                        foreach($account_group_children->accountLedgers as $account_ledger) {
                            $amount = AccountLedgerTransaction::selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                            ->where('ledger_id', $account_ledger->id)
                            ->whereDate('date', '>=', $date->from_date)
                            ->whereDate('date', '<=', $date->to_date)->first();
                            $total_amount += ($amount->total_debit - $amount->total_credit);
                        }
                    }
                }
                
                if($account_group->accountLedgers) {
                    foreach($account_group->accountLedgers as $account_ledger) {
                        $amount = AccountLedgerTransaction::selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                        ->where('ledger_id', $account_ledger->id)
                        ->whereDate('date', '>=', $date->from_date)
                        ->whereDate('date', '<=', $date->to_date)->first();
                        $total_amount += ($amount->total_debit - $amount->total_credit);
                    }
                }
            }
        return $total_amount;
    }
    
    public static function headAccountSummary($nature)
    {
          $settingDate =Helper::activeYear();

        return AccountGroup::where('account_group_nature', $nature)
        ->whereNull('account_group_under_id')
        ->with(['childrenCategories.accountLedgers.summary' => function($summary) use ($settingDate){
            // $summary->where('financial_date', $settingDate);
        }])
        ->with(['accountLedgers.summary' => function($summary) use ($settingDate){
            // $summary->where('financial_date', $settingDate);
        }])
        ->get()
        ->map(function($expenseItem, $key){
            $amount = 0;
            if($expenseItem->accountLedgers){
                $amount +=  (new Helper)->ledgerCal($expenseItem->accountLedgers, $amount);
            }
            if($expenseItem->childrenCategories){
                $amount =  (new Helper)->recursiveFunction($expenseItem->childrenCategories, $amount);
            }
            return [
                'name' => $expenseItem->account_group_name,
                'amount' => $amount
            ];
        })
        ;

    }

    public function ledgerCal($allData, $amount)
    {
        foreach ($allData as $key => $ledger) {
            // $amount +=optional($ledger->summary)->grand_total??0;
            $amount +=optional($ledger->summeries)->sum('grand_total')??0;
        }
        return $amount;
    }


    public function recursiveFunction($accountGroups, $amount)
    {
        foreach ($accountGroups as $key => $group) {
            if($group->accountLedgers){
                $amount =  (new Helper)->ledgerCal($group->accountLedgers, $amount);
            }
            if($group->children){
                $amount = (new Helper)->childRecursiveFunction($group->children, $amount);
            }
        }
        return $amount;
    }


    public function childRecursiveFunction($accountGroups, $amount)
    {
        foreach ($accountGroups as $key => $group) {
            if($group->accountLedgers){
                $amount = (new Helper)->ledgerCal($group->accountLedgers, $amount);
            }
            if($group->children){
                $amount = (new Helper)->childRecursiveFunction($group->children, $amount);
            }
        }
        return  $amount;
    }
    
    public static function NoToWord(float $number)
    {
        $number = abs($number);
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $digits = array('', 'Hundred','Thousand','Lac', 'Crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees : '') . $paise;
    }
    
    public static function upserpermission($route='')
    {
        $user =   Auth::guard()->user();
        $permissions = DB::table('permissions')
        ->join('userpermission','userpermission.permission_id','=','permissions.id')
        ->where('permissions.name',$route)
        ->where('userpermission.user_id',$user->id)
        ->where('userpermission.status',1)
        ->get();
        //return print_r($permissions);
        //return $user->id;
        $notification = array(
            'messege'   => 'Access Denied!',
            'alert-type' => 'warning'
        );
        //return redirect('/')->with($notification);
        if(count($permissions) >0){
            return false;
        }else{
            //return 'no';
            return true;
        }
        //(new Helper)::upserpermission(Route::getFacadeRoot()->current()->uri());
        //return redirect('/')->with($notification);
        
    }
}
?>
