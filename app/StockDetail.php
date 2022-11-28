<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class StockDetail extends Model
{
    use AutoTimeStamp;

    protected $table ='stock_details';

    protected $guarded=['id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }


}
