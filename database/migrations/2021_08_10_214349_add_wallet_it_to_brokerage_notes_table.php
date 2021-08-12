<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletItToBrokerageNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brokerage_notes', function (Blueprint $table) {
            $table->uuid('wallet_id')->nullable();
            $table->dropColumn('user_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brokerage_notes', function (Blueprint $table) {
            $table->dropColumn('wallet_id');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('users')->on('id')->onDelete('cascade');
        });
    }
}
