<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForigenKeyToPurchasesAddListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases_add_lists', function (Blueprint $table) {
            $table->dropColumn('godwn_name');
            $table->dropColumn('salesman_name');
            $table->unsignedBigInteger('godown_id')->after('product_id_list')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('sale_name_id')->after('godown_id')->nullable();
            $table->foreign('sale_name_id')->references('id')->on('sale_mens');
        });

        Schema::table('demo_product_add_on_vouchers', function (Blueprint $table) {
            $table->dropColumn('item_name');
            $table->dateTime('date');
            $table->unsignedBigInteger('item_id')->after('page_name')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_add_lists', function (Blueprint $table) {
            //
        });
    }
}
