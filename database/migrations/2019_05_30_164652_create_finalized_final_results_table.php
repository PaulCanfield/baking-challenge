<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizedFinalResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finalized_final_results', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('season_id');

            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('season_id')->references('id')->on('seasons');

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
        Schema::dropIfExists('finalized_final_results');
    }
}
