<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];

    public function transactionable()
    {
        return $this->morphTo();
    }
}
