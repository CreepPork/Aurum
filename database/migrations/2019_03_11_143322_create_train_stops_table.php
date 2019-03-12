<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('train_stops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');

            $table->double('position_X');
            $table->double('position_Y');

            $table->unsignedBigInteger('line_id')->index();
            $table->foreign('line_id')->references('id')->on('lines');

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
        Schema::dropIfExists('train_stops');
    }
}
