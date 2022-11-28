<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->decimal('stock_qty',8,2)->default(0);
            $table->decimal('purchase_qty',8,2)->default(0);
            $table->decimal('purchase_return_qty',8,2)->default(0);
            $table->decimal('sell_qty',8,2)->default(0);
            $table->decimal('sell_return_qty',8,2)->default(0);
            $table->decimal('grand_total',8,2)->virtualAs('(stock_qty - (purchase_qty + purchase_return_qty + sell_qty + sell_return_qty))');
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
        Schema::dropIfExists('item_counts');
    }
}
