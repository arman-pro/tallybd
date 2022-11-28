<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];
}
