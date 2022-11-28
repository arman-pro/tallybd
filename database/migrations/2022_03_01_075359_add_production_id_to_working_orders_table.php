<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductionIdToWorkingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('working_orders', function (Blueprint $table) {
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('production_id')->nullable()->after('created_by');
            $table->foreign('production_id')->references('id')->on('productions');
        });
        Schema::table('productions', function (Blueprint $table) {
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('working_id')->nullable()->after('created_by');
            $table->foreign('working_id')->references('id')->on('working_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('working_orders', function (Blueprint $table) {
            //
        });
    }
}
