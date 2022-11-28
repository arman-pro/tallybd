<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChnageFiledToStockDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_details', function (Blueprint $table) {
            $table->dropColumn('godown_name');
            $table->dropColumn('item_name');
            $table->dropColumn('qty');
            $table->dropColumn('purchases_price');
            $table->dropColumn('total_pruchases_price');
            $table->dropColumn('sale_price');
            $table->dropColumn('total_sale_price');
            $table->unsignedBigInteger('item_id')->after('id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('godown_id')->after('item_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_details', function (Blueprint $table) {
            $table->dropColumn('godown_name');
            $table->dropColumn('item_name');
            $table->dropColumn('qty');
            $table->dropColumn('purchases_price');
            $table->dropColumn('total_pruchases_price');
            $table->dropColumn('sale_price');
            $table->dropColumn('total_sale_price');
        });
    }
}
