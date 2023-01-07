<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
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
    
    // if have a purchase which is payment by this
    public function purchase()
    {
        return $this->belongsTo(PurchasesAddList::class, 'vo_no', 'cash_payment_vo_no');
    }

}
