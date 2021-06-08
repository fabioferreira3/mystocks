<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveHoofGainColumnFromMonthlyResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_results', function (Blueprint $table) {
            $table->dropColumn('hoof_gain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_results', function (Blueprint $table) {
            $table->boolean('hoof_gain')->default(false);
        });
    }
}
