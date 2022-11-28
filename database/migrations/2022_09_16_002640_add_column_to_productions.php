<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropForeign(['working_id']);
             
             $table->foreign('working_id')->references('id')->on('working_orders')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        
        // working_orders table
        Schema::table('working_orders', function (Blueprint $table) {
             $table->dropForeign(['production_id']);
              $table->foreign('production_id')->references('id')->on('productions')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('working_id');
        });
        
         Schema::table('working_orders', function (Blueprint $table) {
              $table->dropConstrainedForeignId('production_id');
        });
    }
}
