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

    public function demo_product_productions()
    {
        return $this->hasMany(DemoProductProduction::class, 'vo_no', 'vo_no')->where('page_name','1');
    }

    // public function stocks()
    // {
    //     return $this->hasMany(class)
    // }
}
