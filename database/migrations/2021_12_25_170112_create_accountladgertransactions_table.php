<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLedgerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AccountLedgerTransactions', function (Blueprint $table) {
            $table->id();
            $table->string('account_ledger_id');
            $table->string('account_name');
            $table->string('account_ledger__transaction_id');
            $table->string('Debit')->nullable();
            $table->string('Credit')->nullable();
            $table->string('newbalcence')->nullable();
            $table->string('newbalcence_type')->nullable();
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
        Schema::dropIfExists('AccountLedgerTransactions');
    }
}
