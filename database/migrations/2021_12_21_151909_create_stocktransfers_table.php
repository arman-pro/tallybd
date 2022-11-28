<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocktransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocktransfers', function (Blueprint $table) {
            $table->id();
            $table->string('product_id_list');
            $table->string('date');
            $table->string('reference_txt')->nullable();
            $table->string('location_form');
            $table->string('location_to');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('stocktransfers');
    }
}
