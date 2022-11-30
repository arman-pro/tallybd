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

    public function demo_product_productions()
    {
        return $this->hasMany(DemoProductProduction::class, 'vo_no', 'vo_no')->where('page_name', 2);
    }
}
