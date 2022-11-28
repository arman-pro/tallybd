<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionWorkoOrder extends Model
{
    protected $fillable = [
	'date',
	'production_workorder_vo_id',
	'refer_no',
	];
}
