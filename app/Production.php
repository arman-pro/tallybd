<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];


    public function working()
    {
        return $this->belongsTo(WorkingOrder::class, 'working_id', 'id');
    }

    public function stock()
    {
        return $this->morphMany(StockHistory::class, 'stockable');
    }
}
