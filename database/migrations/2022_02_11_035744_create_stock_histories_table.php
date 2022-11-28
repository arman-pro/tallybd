<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('stockable_id');
            $table->string('stockable_type');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('godown_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->decimal('in_qty', 8, 2)->nullable();
            $table->decimal('out_qty', 8, 2)->nullable();
            $table->decimal('total_qty', 8, 2)->nullable()->virtualAs('in_qty - out_qty');
            $table->decimal('average_price', 8, 2)->nullable();
            $table->decimal('total_average_price', 8, 2)->nullable()->virtualAs('total_qty * average_price');
            $table->date('date');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('stock_histories');
    }
}
