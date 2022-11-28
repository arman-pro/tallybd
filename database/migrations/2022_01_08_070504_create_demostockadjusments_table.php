<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemostockadjusmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demostockadjusments', function (Blueprint $table) {
            $table->id();
            $table->string('id_row');
            $table->string('adjustmen_vo_id');
            $table->string('page_name')->nullable();
            $table->string('item_name');
            $table->string('godown_id')->nullable();
            $table->string('sales_price');
            $table->string('qty')->default(1);
            $table->string('subtotal_on_product');
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
        Schema::dropIfExists('demostockadjusments');
    }
}
