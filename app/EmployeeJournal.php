<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class EmployeeJournal extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];

    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function details()
    {
        // return $this->belongsTo(class)
    }
}
