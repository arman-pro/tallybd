<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Godown extends Model
{
    protected $fillable = [
        'godown_id',
        'name',
        'description',

    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'godown_id', 'id');
    }

    public function stockDetails()
    {
        return $this->hasMany(StockDetail::class, 'godown_id', 'id');
    }
}
