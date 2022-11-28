<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use AutoTimeStamp;
    protected $guarded=['id'];


    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class,'payment_by');
    }

    public function details()
    {
        return $this->hasMany(SalaryDetails::class, 'salary_id', 'id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

}
