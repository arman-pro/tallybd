<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use AutoTimeStamp;
    protected $guarded=['id'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id','id')->select('id', 'name', 'category_id', 'sales_price');
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class, 'godown_id', 'id')->select('id', 'name');
    }

    public function stockable()
    {
        return $this->morphTo();
    }
}
