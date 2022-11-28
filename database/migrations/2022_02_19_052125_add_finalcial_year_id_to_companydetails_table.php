<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinalcialYearIdToCompanydetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companydetails', function (Blueprint $table) {
            // $table->dropForeign(['old__year_id']);
            // $table->dropColumn('old__year_id');
            $table->unsignedBigInteger('financial_year_id')->after('id')->nullable();
            $table->foreign('financial_year_id')->references('id')->on('financial_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companydetails', function (Blueprint $table) {
            //
        });
    }
}
