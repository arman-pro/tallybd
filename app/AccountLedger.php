<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use App\CustomTrait\GlobalScope;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AccountLedger extends Model
{
    use AutoTimeStamp, GlobalScope;

    protected $guarded=['id'];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function accountGroupName()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id', 'id');
    }

    public function summary()
    {
        return $this->hasOne(LedgerSummary::class, 'ledger_id', 'id');
    }
    
    public function summeries()
    {
        return $this->hasMany(LedgerSummary::class, 'ledger_id', 'id');
    }

    public function transitions()
    {
        return $this->hasMany(AccountLedgerTransaction::class, 'ledger_id', 'id');
    }
    
    /**
     * if delete able 
     */
    public function is_deleteable()
    {
        return $this->summary()->grand_total <= 0;
    }
 
    
    public function transitions_unique($column, $date=null)
    {
        if($date)
            return AccountLedgerTransaction::where('ledger_id', $this->id)->where('date', '<=', $date)->groupBy($column)->selectRaw("sum(debit) as debit, sum(credit) as credit")->get();
        else
            return AccountLedgerTransaction::where('ledger_id', $this->id)->groupBy($column)->selectRaw("sum(debit) as debit, sum(credit) as credit")->get();
        // if($date)
        //     return $this->transitions->where('date', '<=', $date)->unique($column);
        // else
        //     return $this->transitions->unique($column);
    }
    
    public function received_payment_amount($end_date)
    {
        $collection = $this->transitions_unique('account_ledger__transaction_id', $end_date);
        return $collection->sum('debit') - $collection->sum('credit');
        // return $collection->sum('debit') - $collection->sum('credit');
    }
}
