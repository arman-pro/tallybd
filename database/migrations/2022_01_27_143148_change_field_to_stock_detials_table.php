<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldToStockDetialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::rename('stockdetials', 'stock_details');
        // Schema::table('stock_details', function (Blueprint $table) {
        //     $table->dropColumn('godown_name');
        //     $table->dropColumn('item_name');
        //     $table->decimal('qty', 8,2)->nullable()->change();
        //     $table->decimal('purchases_price', 12,2)->nullable()->change();
        //     $table->decimal('total_pruchases_price', 12,2)->nullable()->change();
        //     $table->decimal('sale_price', 12,2)->nullable()->change();
        //     $table->decimal('total_sale_price', 12,2)->nullable()->change();
        //     $table->unsignedBigInteger('item_id')->after('id');
        //     $table->foreign('item_id')->references('id')->on('items');
        //     $table->unsignedBigInteger('godown_id')->after('item_id')->nullable();
        //     $table->foreign('godown_id')->references('id')->on('godowns');
        //     $table->unsignedBigInteger('created_by');
        //     $table->foreign('created_by')->references('id')->on('users');
        //     $table->unsignedBigInteger('updated_by');
        //     $table->foreign('updated_by')->references('id')->on('users');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_details', function (Blueprint $table) {

        });
    }
}
