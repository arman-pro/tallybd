<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockdetails', function (Blueprint $table) {
            $table->id();
            $table->string('st_id');
            $table->string('item_name');
            $table->string('godown_name');
            $table->string('qty')->nullable();
            $table->string('purchases_price')->nullable();
            $table->string('total_pruchases_price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('total_sale_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stockdetails');
    }
}
