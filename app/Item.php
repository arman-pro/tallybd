<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use App\CustomTrait\GlobalScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Item extends Model
{
    use AutoTimeStamp, GlobalScope;

    protected $guarded = ['id'];
    
    // protected $appends = ['avg_purchase_price'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
    }
    
    public function sales()
    {
        return $this->hasMany(SalesAddList::class, 'id', 'item_id');
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class);
    }

    public function purchase()
    {
        return $this->hasMany(PurchaseDetails::class, 'item_id');
    }

    public function demoProductAddOnVoucher()
    {
        return $this->hasMany(DemoProductAddOnVoucher::class, 'item_id', 'id');
    }

    public function stock()
    {
        return $this->morphOne(StockHistory::class, 'stockable');
    }
    
    public function stocks()
    {
        return $this->hasMany(StockHistory::class, 'item_id', 'id');
    }


    public function count()
    {
        return $this->hasOne(ItemCount::class, 'item_id', 'id');
    }
    
    public function get_avg_purchase_price($to_date) 
    {
        $history = StockHistory::selectRaw('SUM(total_average_price) as total_average_price, SUM(in_qty) as in_qty')
        ->where('item_id', $this->id)
        ->whereNotNull('in_qty')
        ->where('in_qty', '!=', 0)
        ->whereDate('date', '<=', $to_date)
        ->groupBy('item_id')
        ->first();
        return ($history->total_average_price ?? 1) / ($history->in_qty ?? 1);
    }

}
