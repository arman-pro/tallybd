<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class DemoProductAddOnVoucher extends Model
{

    use AutoTimeStamp;

    protected $guarded = ['id'];
    
    protected $appends = ['discount_amount', 'main_price_get'];


    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    
    public function getDiscountAmountAttribute() 
    {
        $price = !$this->main_price ? $this->price : $this->main_price;
        if($this->discount_type === 'bdt') {
            return $this->discount ?? 0;
        }else if($this->discount_type === 'percent') {
            return (($this->discount ?? 0) / ($this->qty * $price)) * 100 ?? 0;
        }
        return 0;
    }
    
    public function getMainPriceGetAttribute($value)
    {
        if(is_null($this->main_price)) {
            return $this->price;
        }else {
            return $this->main_price;
        }
    }




}
