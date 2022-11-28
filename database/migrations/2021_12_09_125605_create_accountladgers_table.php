<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AccountLedgers', function (Blueprint $table) {
            $table->id();
            $table->string('account_ledger_id');
            $table->string('account_name');
            $table->string('account_ledger_group_name')->nullable();
            $table->string('account_ledger_phone');
            $table->string('account_ledger_email')->nullable();
            $table->string('account_ledger_opening_balance')->nullable();
            $table->string('debit_credit');
            $table->string('account_ledger_address')->nullable();
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
        Schema::dropIfExists('AccountLedgers');
    }
}
