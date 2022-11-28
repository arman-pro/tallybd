<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanydetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companydetails', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contry_name');
            $table->string('mailing_name')->nullable();
            $table->string('email_id');
            $table->string('website_name');
            $table->string('phone')->nullable();
            $table->string('mobile_number');
            $table->string('booking_date')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_des')->nullable();
            $table->string('company_logo');
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
        Schema::dropIfExists('companydetails');
    }
}
