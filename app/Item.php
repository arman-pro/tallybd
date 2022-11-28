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


	// protected $fillable = [
    //     'item_code',
    //     'item_name',
    //     'unit_name',
    //     'how_many_unit',
    //     'catagory_name',
    //     'godwn_id',
    //     'purchases_price',
    //     'sales_price',
    //     'previous_stock',
    //     'total_previous_stock_value',
    //     'item_description',
    // ];

    protected $guarded = ['id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
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

}
// ->where('purchaseable_type','App\PurchasesAddList')
