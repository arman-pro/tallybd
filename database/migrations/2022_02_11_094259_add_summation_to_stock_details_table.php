<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSummationToStockDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_details', function (Blueprint $table) {

            $table->decimal('total_purchase_price')->virtualAs('qty*purchases_price')->after('purchases_price')->nullable();
            $table->decimal('total_sale_price')->virtualAs('qty*sale_price')->after('sale_price')->nullable();
        });
        Schema::table('stock_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('godown_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
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

        });
    }
}
