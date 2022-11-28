<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class adminsubmenu extends Model
{
    protected $table='adminsubmenu';
    protected $fillable = [
                            'mainmenuId',
                            'submenuname',
                            'serialno',
                            'routeName',
                            ];
}
