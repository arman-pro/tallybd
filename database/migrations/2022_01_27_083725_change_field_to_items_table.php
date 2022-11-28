<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldToItemsTable extends Migration
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
            $table->string('name');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->unsignedBigInteger('godown_id');
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->decimal('purchases_price',12,2)->default(0.00);
            $table->decimal('sales_price',12,2)->default(0.00);
            $table->decimal('previous_stock',12,2)->default(0.00);
            $table->decimal('total_previous_stock_value',12,2)->default(0.00);
            $table->decimal('item_description')->nullable();
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
        Schema::create('items', function (Blueprint $table) {
            // $table->dropColumn('unit_name');
            // $table->dropColumn('catagory_name');
            // $table->dropColumn('godwn_id');
        });
    }
}
