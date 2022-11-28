<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeJournalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_journal_details', function (Blueprint $table) {
            $table->id();
            $table->string('vo_no');
            $table->string('id_row');
            $table->date('date');
            $table->tinyInteger('drcr');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('ledger_id')->nullable();
            $table->foreign('ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
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
        Schema::dropIfExists('employee_journal_details');
    }
}
