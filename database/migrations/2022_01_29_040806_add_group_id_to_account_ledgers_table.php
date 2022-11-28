<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdToAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->dropColumn('account_ledger_group_name');
            $table->unsignedBigInteger('account_group_id')->after('account_ledger_id')->nullable();
            $table->foreign('account_group_id')->references('id')->on('account_groups');
        });
        Schema::table('account_ledger_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('ledger_id')->after('account_ledger_id')->nullable();
            $table->foreign('ledger_id')->references('id')->on('account_ledgers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_ledgers', function (Blueprint $table) {
            //
        });
    }
}
