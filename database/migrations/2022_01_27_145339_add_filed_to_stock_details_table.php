<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledToStockDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_details', function (Blueprint $table) {
            $table->decimal('qty', 8,2)->nullable();
            $table->decimal('purchases_price', 12,2)->nullable();
            $table->decimal('total_pruchases_price', 12,2)->nullable();
            $table->decimal('sale_price', 12,2)->nullable();
            $table->decimal('total_sale_price', 12,2)->nullable();
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
            //
        });
    }
}
