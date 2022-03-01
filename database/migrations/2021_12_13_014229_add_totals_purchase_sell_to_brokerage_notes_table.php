<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalsPurchaseSellToBrokerageNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brokerage_notes', function (Blueprint $table) {
            $table->decimal('total_purchased', 10, 2)->default(0);
            $table->decimal('total_sold', 10, 2)->default(0);
            $table->dropColumn('total_value')->default(0);
            $table->decimal('total_operations_value', 10, 2)->default(0);
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
            $table->dropColumn('total_purchased');
            $table->dropColumn('total_sold');
            $table->dropColumn('total_operations_value');
            $table->decimal('total_value', 10, 2);
        });
    }
}
