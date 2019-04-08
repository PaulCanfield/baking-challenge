<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePredictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('episode_id');
            $table->unsignedBigInteger('baker_id')->nullable();
            $table->unsignedBigInteger('result_id');
            $table->unsignedBigInteger('owner_id');

            $table->string('notes')->nullable();

            $table->timestamps();

            $table->foreign('result_id')->references('id')->on('results');
            $table->foreign('baker_id')->references('id')->on('bakers');
            $table->foreign('episode_id')->references('id')->on('episodes');
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predictions');
    }
}
