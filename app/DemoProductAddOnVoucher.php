<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class DemoProductAddOnVoucher extends Model
{

    use AutoTimeStamp;

    protected $guarded = ['id'];


    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }




}
