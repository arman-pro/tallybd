<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesAddListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PurchasesAddLists', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('product_id_list');
            $table->string('godown_id');
            $table->string('SaleMan_name')->nullable();
            $table->string('account_ladger');
            $table->string('order_no')->nullable();
            $table->string('other_bill')->nullable();
            $table->string('discount_total')->nullable();
            $table->string('pre_amount')->nullable();
            $table->string('shipping_details')->nullable();
            $table->string('delivered_to_details')->nullable();
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
        Schema::dropIfExists('PurchasesAddLists');
    }
}
