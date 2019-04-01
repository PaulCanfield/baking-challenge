<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodeResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_results', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('episode_id');
            $table->unsignedBigInteger('result_id');
            $table->unsignedBigInteger('baker_id')->nullable();
            $table->string('notes')->nullable();

            $table->timestamps();

            $table->foreign('result_id')->references('id')->on('results');
            $table->foreign('baker_id')->references('id')->on('bakers');
            $table->foreign('episode_id')->references('id')->on('episodes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episode_results');
    }
}
