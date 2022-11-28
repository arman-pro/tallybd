<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class PurchasesReturnAddList extends Model
{
    use AutoTimeStamp;

    protected $guarded =['id'];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_id','id');
    }

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'account_ledger_id');
    }

    public function saleMen()
    {
        return $this->belongsTo(SaleMen::class, 'sale_name_id');
    }

    public function godown()
    {
        return $this->belongsTo(Godown::class, 'godown_id');
    }

    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function ledgerSummer()
    {
        return $this->belongsTo(LedgerSummary::class, 'ledger_id');
    }

    public function detailsProduct()
    {
        return $this->morphMany(PurchaseDetails::class, 'purchaseable');
    }

    public function demoProducts()
    {
        return $this->hasMany(DemoProductAddOnVoucher::class,'product_id_list', 'product_id_list');
    }

    public function stock()
    {
        return $this->morphMany(StockHistory::class, 'stockable');
    }

}
