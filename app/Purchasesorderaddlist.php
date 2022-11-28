<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchasesOrderAddList extends Model
{
    protected $fillable = [
        'date',
        'product_id_list',
        'godown_id',
        'salesman_name',
        'account_ladger',
        'order_no',
        'other_bill',
        'discount_total',
        'pre_amount',
        'shipping_details',
        'shipping_details',
    ];
}
