<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class WorkingOrder extends Model
{
    use AutoTimeStamp;
    protected $guarded =['id'];

    public function stock()
    {
        return $this->morphMany(StockHistory::class, 'stockable');
    }
    public function production()
    {
        return $this->hasOne(Production::class, 'working_id', 'id');
    }

    // public function stocks()
    // {
    //     return $this->hasMany(class)
    // }
}
