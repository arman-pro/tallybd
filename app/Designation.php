<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use AutoTimeStamp;
    protected $guarded =['ids'];
}
