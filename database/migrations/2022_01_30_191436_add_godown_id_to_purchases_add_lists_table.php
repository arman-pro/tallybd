<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddGodownIdToPurchasesAddListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // DB::statement("ALTER TABLE `purchases_add_lists` ADD CONSTRAINT
        //  `purchases_add_lists_account_ledger_id_foreign` FOREIGN KEY (`account_ledger_id`) REFERENCES `account_ledgers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT");
        // Schema::table('purchases_add_lists', function (Blueprint $table) {
        //     $table->unsignedBigInteger('account_ledger_id')->change()->nullable();
        //     $table->foreign('account_ledger_id')->references('id')->on('account_ledgers');
        // });
        // DB::statement("ALTER TABLE `sales_add_lists` ADD CONSTRAINT `sales_add_lists_account_ledger_id_foreign` FOREIGN KEY (`account_ledger_id`) REFERENCES `account_ledgers`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT");

        Schema::table('sales_add_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('account_ledger_id')->nullable()->after('sale_man_id');
            $table->foreign('account_ledger_id')->references('id')->on('account_ledgers');
        });

        Schema::table('purchases_return_add_lists', function (Blueprint $table) {
            $table->dropColumn('godwn_name');
            $table->dropColumn('salesman_name');
            $table->dropColumn('account_ladger');

            $table->unsignedBigInteger('account_ledger_id')->nullable()->after('product_id_list');
            $table->foreign('account_ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('godown_id')->after('account_ledger_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('sale_man_id')->after('godown_id')->nullable();
            $table->foreign('sale_man_id')->references('id')->on('sale_mens');
            $table->decimal('grand_total', 12, 2);
        });

        Schema::table('purchases_order_add_lists', function (Blueprint $table) {
            $table->dropColumn('godwn_name');
            $table->dropColumn('salesman_name');
            $table->dropColumn('account_ladger');

            $table->unsignedBigInteger('account_ledger_id')->nullable()->after('product_id_list');
            $table->foreign('account_ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('godown_id')->after('account_ledger_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('sale_man_id')->after('godown_id')->nullable();
            $table->foreign('sale_man_id')->references('id')->on('sale_mens');
            $table->decimal('grand_total', 12, 2);
        });

        Schema::table('sales_order_add_lists', function (Blueprint $table) {
            $table->dropColumn('godwn_name');
            $table->dropColumn('salesman_name');
            $table->dropColumn('account_ladger');

            $table->unsignedBigInteger('account_ledger_id')->nullable()->after('product_id_list');
            $table->foreign('account_ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('godown_id')->after('account_ledger_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('sale_man_id')->after('godown_id')->nullable();
            $table->foreign('sale_man_id')->references('id')->on('sale_mens');
            $table->decimal('grand_total', 12, 2);
        });

        Schema::table('sales_return_add_lists', function (Blueprint $table) {
            $table->dropColumn('godwn_name');
            $table->dropColumn('salesman_name');
            $table->dropColumn('account_ladger');

            $table->unsignedBigInteger('account_ledger_id')->nullable()->after('product_id_list');
            $table->foreign('account_ledger_id')->references('id')->on('account_ledgers');
            $table->unsignedBigInteger('godown_id')->after('account_ledger_id')->nullable();
            $table->foreign('godown_id')->references('id')->on('godowns');
            $table->unsignedBigInteger('sale_man_id')->after('godown_id')->nullable();
            $table->foreign('sale_man_id')->references('id')->on('sale_mens');
            $table->decimal('grand_total', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases_add_lists', function (Blueprint $table) {
            //
        });
    }
}
