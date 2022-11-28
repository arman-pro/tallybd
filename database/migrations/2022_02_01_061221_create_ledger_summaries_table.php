<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->foreign('ledger_id')->references('id')->on('account_ledgers');
            $table->date('date');
            $table->decimal('debit', 12, 2)->nullable();
            $table->decimal('credit', 12, 2)->nullable();
            $table->decimal('grand_total', 12,2)->virtualAs('debit - credit');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('ledger_summaries');
    }
}
