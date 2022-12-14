<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Receive extends Model
{
    protected $guarded =['id'];

    use AutoTimeStamp;

    public function paymentMode()
    {
        return $this->belongsTo(AccountLedger::class, 'payment_mode_ledger_id', 'id');
    }

    public function accountMode()
    {
        return $this->belongsTo(AccountLedger::class, 'account_name_ledger_id','id');
    }
    
    public function sale()
    {
        return $this->belongsTo(SalesAddList::class, 'vo_no', 'cash_receive_vo_no');
    }


}
