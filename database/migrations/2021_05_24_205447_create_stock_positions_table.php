<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('stock_id');
            $table->integer('position')->default(0);
            $table->decimal('total_value', 10, 2);
            $table->timestamps();

            $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_positions');
    }
}
