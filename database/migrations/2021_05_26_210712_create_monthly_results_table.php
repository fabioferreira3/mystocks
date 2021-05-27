<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('total_value', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->boolean('hoof_gain')->default(false);
            $table->decimal('previous_result', 10, 2)->nullable();
            $table->date('at_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_results');
    }
}
