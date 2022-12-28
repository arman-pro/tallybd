<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use App\CustomTrait\GlobalScope;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use AutoTimeStamp, GlobalScope;
    protected $guarded =[''];


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function reference()
    {
        return $this->belongsTo(Employee::class, 'reference_id');
    }

    public function journals()
    {
        return $this->hasMany(EmployeeJournalDetails::class, 'employee_id');
    }
    
    public function summary()
    {
        return $this->hasOne(LedgerSummary::class, 'ledger_id', 'id');
    }
}
