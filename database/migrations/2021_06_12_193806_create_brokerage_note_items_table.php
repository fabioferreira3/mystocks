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
            $table->string('type');
            $table->decimal('taxes', 10, 2);
            $table->decimal('net_value', 10, 2);
            $table->decimal('total_value', 10, 2);
            $table->timestamps();

            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('brokerage_note_id')->references('id')->on('brokerage_notes');
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
