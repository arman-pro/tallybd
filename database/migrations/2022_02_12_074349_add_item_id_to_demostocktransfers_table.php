<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemIdToDemostocktransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demostocktransfers', function (Blueprint $table) {
            $table->dropColumn('item_name');
            $table->renameColumn('vo_id', 'vo_no');
            $table->renameColumn('id_code', 'row_id');
            $table->unsignedBigInteger('item_id')->after('id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('godown_id')->after('item_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->date('date')->after('item_id');
            $table->unsignedBigInteger('created_by')->after('id')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->after('id')->nullable();
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
        Schema::table('demostocktransfers', function (Blueprint $table) {
            //
        });
    }
}
