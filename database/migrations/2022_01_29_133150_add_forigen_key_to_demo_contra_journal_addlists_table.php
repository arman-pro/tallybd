<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForigenKeyToDemoContraJournalAddlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_contra_journal_addlists', function (Blueprint $table) {
            $table->dateTime('date');
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->foreign('ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::table('demo_contra_journal_addlists', function (Blueprint $table) {
            //
        });
    }
}
