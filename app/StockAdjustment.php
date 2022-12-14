<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use AutoTimeStamp;
    protected $table = 'stock_adjustments';
    protected $guarded=['id'];

    public function stock()
    {
        return $this->morphMany(StockHistory::class, 'stockable');
    }

    public function demo_stock_details()
    {
        return $this->hasMany(Demostockadjusment::class, 'adjustmen_vo_id', 'adjustmen_vo_id');
    }

}
