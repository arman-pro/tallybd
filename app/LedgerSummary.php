<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class LedgerSummary extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];


    public function accountLedger()
    {
        return $this->belongsTo(AccountLedger::class, 'ledger_id', 'id');
    }
}
