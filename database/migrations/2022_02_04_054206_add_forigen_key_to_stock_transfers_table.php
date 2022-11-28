<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForigenKeyToStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {

            $table->unsignedBigInteger('location_form')->nullable()->after('id');
            $table->foreign('location_form')->references('id')->on('godowns');
            $table->unsignedBigInteger('location_to')->nullable()->after('location_form');
            $table->foreign('location_to')->references('id')->on('godowns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            //
        });
    }
}
