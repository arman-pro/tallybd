<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_m_s', function (Blueprint $table) {
            $table->string('provider', 100)->nullable()->after('id');
            $table->boolean('is_active')->default(false)->after('smsType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_ms_', function (Blueprint $table) {
            //
        });
    }
}
