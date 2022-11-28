<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('accountgroups', 'account_groups');
        Schema::rename('AccountLedgers', 'account_ledgers');
        Schema::rename('AccountLedgerTransactions', 'account_ledger_transactions');
        Schema::rename('ProductionWorkoOrders', 'production_work_orders');
        Schema::rename('PurchasesAddLists', 'purchases_add_lists');
        Schema::rename('PurchasesOrderAddLists', 'purchases_order_add_lists');
        Schema::rename('PurchasesReturnAddLists', 'purchases_return_add_lists');
        Schema::rename('SalesAddLists', 'sales_add_lists');
        Schema::rename('salesmen', 'sale_mens');
        Schema::rename('SalesOrderAddLists', 'sales_order_add_lists');
        Schema::rename('SalesReturnAddLists', 'sales_return_add_lists');
        Schema::rename('StockAdjustments', 'stock_adjustments');
        Schema::rename('stocktransfers', 'stock_transfers');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
