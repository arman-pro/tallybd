<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use AutoTimeStamp;
    protected $fillable = [
        'product_id_list',
        'date',
        'reference_txt',
        'location_form',
        'location_to',
        'description',
    ];

    public function locationForm()
    {
        return $this->belongsTo(Godown::class, 'location_form');
    }

    public function locationTo()
    {
        return $this->belongsTo(Godown::class, 'location_to');
    }

    public function stock()
    {
        return $this->morphMany(StockHistory::class, 'stockable');
    }
}
