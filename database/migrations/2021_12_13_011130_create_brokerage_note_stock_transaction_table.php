<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerageNoteStockTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokerage_note_stock_transaction', function (Blueprint $table) {
            $table->uuid('brokerage_note_id');
            $table->uuid('stock_transaction_id');
            $table->timestamps();

            $table->unique(['brokerage_note_id', 'stock_transaction_id']);
            $table->foreign('brokerage_note_id')->references('id')->on('brokerage_notes')->onDelete('cascade');
            $table->foreign('stock_transaction_id')->references('id')->on('stock_transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokerage_note_stock_transaction');
    }
}
