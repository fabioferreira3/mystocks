<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerageNoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokerage_note_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('stock_id')->nullable();
            $table->uuid('brokerage_note_id');
            $table->uuid('stock_transaction_id')->nullable();
            $table->string('type');
            $table->integer('amount');
            $table->decimal('taxes', 10, 2);
            $table->decimal('net_value', 10, 2);
            $table->decimal('total_value', 10, 2);
            $table->timestamps();

            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('brokerage_note_id')->references('id')->on('brokerage_notes');
            $table->foreign('stock_transaction_id')->references('id')->on('stock_transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokerage_note_items');
    }
}
