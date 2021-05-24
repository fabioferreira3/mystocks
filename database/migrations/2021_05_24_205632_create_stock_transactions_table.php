<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('stock_position_id');
            $table->tinyInteger('added')->nullable();
            $table->tinyInteger('subtracted')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('stock_position_id')->references('id')->on('stock_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transactions');
    }
}
