<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoProductAddOnVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DemoProductAddOnVouchers', function (Blueprint $table) {
            $table->id();
            $table->string('id_row');
            $table->string('product_id_list');
            $table->string('page_name')->nullable();
            $table->string('item_name');
            $table->string('sales_price');
            $table->string('discount')->nullable();
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
        Schema::dropIfExists('DemoProductAddOnVouchers');
    }
}
