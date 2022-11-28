<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class DemoContraJournalAddlist extends Model
{

    use AutoTimeStamp;
    protected $guarded = ['id'];

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'ledger_id', 'id');
    }


    public function contra()
    {
        return $this->belongsTo(Journal::class, 'contra_id', 'id');
    }
    // public function ()
    // {
    //     return $this->belongsTo(Journal::class, 'contra_id', 'id');
    // }

}
