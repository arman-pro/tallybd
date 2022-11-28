<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class linkpiority extends Model
{
     protected $table='linkpiority';
     protected $fillable =[
                         'adminid',
                         'mainlinkid',
                         'sublinkid',
                         ];
}
