<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use AutoTimeStamp;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function payment()
    {
        return $this->belongsTo(AccountLedger::class,'payment_by', 'id');
    }

    public function receive()
    {
        return $this->belongsTo(AccountLedger::class,'receive_by', 'id');
    }
}
