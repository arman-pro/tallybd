<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class EmployeeJournalDetails extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id');
    }
    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'ledger_id','id');
    }
}
