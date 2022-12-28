<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class SalesAddList extends Model
{
    use AutoTimeStamp;
    protected $guarded = ['id'];


    public function items()
    {
        return $this->hasMany(Item::class, 'item_id','id');
    }

    public function demoProducts()
    {
        return $this->hasMany(DemoProductAddOnVoucher::class,'product_id_list', 'product_id_list');
    }

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'account_ledger_id', 'id');
    }
    
    public function ledgerexpense()
    {
        return $this->belongsTo(AccountLedger::class, 'expense_ledger_id', 'id');
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
        return $this->morphMany(SaleDetails::class, 'saleable');
    }

    public function stock()
    {
        return $this->morphMany(StockHistory::class, 'stockable');
    }
    
     public function account_ledger_transaction()
    {
        return $this->hasMany(AccountLedgerTransaction::class, 'account_ledger__transaction_id', 'product_id_list');
    }
    
    /**
     * main ledger transaction
     */
    public function main_ledger()
    {
        return $this->account_ledger_transaction->where('ledger_id', $this->account_ledger_id)->first();
    }
    /**
     * expense ledger transaction
     */
    public function expense_ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'expense_ledger_id', 'id');
    }
    

}
