<?php

use Domain\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUserToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('wallets', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('monthly_results', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('brokerage_notes', function (Blueprint $table) {
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        $masterUser = User::first();
        DB::table('stock_transactions')->update(['user_id' => $masterUser->id]);
        DB::table('stock_positions')->update(['user_id' => $masterUser->id]);
        DB::table('wallets')->update(['user_id' => $masterUser->id]);
        DB::table('monthly_results')->update(['user_id' => $masterUser->id]);
        DB::table('brokerage_notes')->update(['user_id' => $masterUser->id]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('monthly_results', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('brokerage_notes', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
