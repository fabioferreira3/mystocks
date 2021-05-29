<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountAndTypeToStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn('added');
            $table->dropColumn('subtracted');
            $table->integer('amount');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('amount');
            $table->tinyInteger('added')->nullable();
            $table->tinyInteger('subtracted')->nullable();
        });
    }
}
