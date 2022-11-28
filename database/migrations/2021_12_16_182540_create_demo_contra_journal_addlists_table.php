<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoContraJournalAddlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_contra_journal_addlists', function (Blueprint $table) {
            $table->id();
            $table->string('id_row');
            $table->string('vo_no');
            $table->string('page_name');
            $table->string('account_name');
            $table->string('drcr');
            $table->string('amount');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('demo_contra_journal_addlists');
    }
}
