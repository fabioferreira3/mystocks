<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerageNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brokerage_notes', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('broker_id')->nullable();
            $table->date('date');
            $table->decimal('taxes', 10, 2);
            $table->decimal('net_value', 10, 2);
            $table->decimal('total_value', 10, 2);
            $table->integer('sells')->default(0);
            $table->integer('purchases')->default(0);
            $table->timestamps();

            $table->foreign('broker_id')->references('id')->on('brokers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brokerage_notes');
    }
}
