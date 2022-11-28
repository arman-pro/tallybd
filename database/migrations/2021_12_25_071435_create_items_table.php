<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('item_name');
            $table->string('unit_name');
            $table->integer('how_many_unit')->nullable();
            $table->string('catagory_name');
            $table->string('godwn_id')->nullable();
            $table->string('purchases_price')->nullable();
            $table->string('sales_price')->nullable();
            $table->string('previous_stock')->nullable();
            $table->string('total_previous_stock_value')->nullable();
            $table->string('item_description')->nullable();
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
        Schema::dropIfExists('items');
    }
}
