<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class AccountLedgerTransaction extends Model
{
    use AutoTimeStamp;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function accountName()
    {
        return $this->belongsTo(AccountLedger::class, 'ledger_id', 'id');
    }

}
