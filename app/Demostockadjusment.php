<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class Demostockadjusment extends Model
{
    //protected $table = 'demostocktransfers';

    use AutoTimeStamp;
    protected $guarded= ['id'];
    // protected $fillable = [
    // 	'id_row',
    // 	'adjustmen_vo_id',
	// 	'page_name',
	// 	'item_name',
	// 	'godown_id',
	// 	'sales_price',
	// 	'qty',
	// 	'subtotal_on_product',
	// ];


    public function godown()
    {
        return $this->belongsTo(Godown::class, 'godown_id', 'id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
