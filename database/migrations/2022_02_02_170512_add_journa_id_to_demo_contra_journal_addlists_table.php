<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJournaIdToDemoContraJournalAddlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_contra_journal_addlists', function (Blueprint $table) {
            $table->unsignedBigInteger('journal_id')->nullable()->after('id');
            $table->foreign('journal_id')->references('id')->on('journals');
            $table->unsignedBigInteger('contra_id')->nullable()->after('journal_id');
            $table->foreign('contra_id')->references('id')->on('journals');
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
