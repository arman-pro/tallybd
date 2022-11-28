<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class Department extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];
}
