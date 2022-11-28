<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class adminmainmenu extends Model
{
    protected $table='adminmainmenu';
    protected $fillable = [
                            'Link_Name',
                            'serialNo',
                            'routeName',
                            ];
}
