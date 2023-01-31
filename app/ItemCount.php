<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCount extends Model
{
    protected $guarded=['id'];
    
    //  protected $appends = ['stock_qty'];
    
    public function getGrandTotalAttribute($value) {
        $stock_history = StockHistory::where('item_id', $this->id)->selectRaw('sum(in_qty) as in_qty, sum(out_qty) as out_qty')->first();
        return ($stock_history->in_qty ?? 0) - ($stock_history->out_qty ?? 0);
    }
}
