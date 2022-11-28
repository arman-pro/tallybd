<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use AutoTimeStamp;

    protected $guarded =['id'];
}
