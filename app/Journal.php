<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];


    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function demoDetails()
    {
        return $this->hasMany(DemoContraJournalAddlist::class, 'contra_id', 'id');
    }
    public function joDemoDetails()
    {
        return $this->hasMany(DemoContraJournalAddlist::class, 'journal_id', 'id');
    }

}
