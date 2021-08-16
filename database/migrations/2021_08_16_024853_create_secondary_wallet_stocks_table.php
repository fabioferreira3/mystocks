<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryWalletStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_wallet_stock_position', function (Blueprint $table) {
            $table->uuid('secondary_wallet_id');
            $table->uuid('stock_position_id');
            $table->timestamps();

            $table->unique(['secondary_wallet_id', 'stock_position_id']);
            $table->foreign('secondary_wallet_id')->references('id')->on('secondary_wallets')->onDelete('cascade');
            $table->foreign('stock_position_id')->references('id')->on('stock_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secondary_wallet_stock_position');
    }
}
