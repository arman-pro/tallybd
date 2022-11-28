<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class SaleMen extends Model
{

    use AutoTimeStamp;
    protected $table ='sale_mens';
    protected $guarded =['id'];
    // protected $fillable = [
    //     'salesman_id',
    //     'salesman_name',
    //     'phone',
    //     'email',
    //     'address',

    // ];
}
