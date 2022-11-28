<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesOrderAddList extends Model
{
    protected $fillable = [

        'date',
        'product_id_list',
        'godown_id',
        'sale_name_id ',
        'account_ledger_id',
        'order_no',
        'other_bill',
        'discount_total',
        'pre_amount',
        'shipping_details',
        'delivered_to_details',
        'grand_total',
        'order_status',
        'md_signature',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_id','id');
    }

    public function ledgers()
    {
        return $this->belongsTo(AccountLedger::class, 'account_ledger_id', 'id');
    }

    public function demoProducts()
    {
        return $this->hasMany(DemoProductAddOnVoucher::class,'product_id_list', 'product_id_list');
    }
}
