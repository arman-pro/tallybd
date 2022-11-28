<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class SalaryDetails extends Model
{
    use AutoTimeStamp;
    protected $guarded=['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    public function salary_()
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }
    
  
}
