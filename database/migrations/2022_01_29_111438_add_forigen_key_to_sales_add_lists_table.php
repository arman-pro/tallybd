<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForigenKeyToSalesAddListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_add_lists', function (Blueprint $table) {
            $table->dropColumn('godwn_name');
            $table->dropColumn('salesman_name');
            $table->unsignedBigInteger('godown_id')->after('product_id_list')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('sale_name_id')->after('godown_id')->nullable();
            $table->foreign('sale_name_id')->references('id')->on('sale_mens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_add_lists', function (Blueprint $table) {
            //
        });
    }
}
