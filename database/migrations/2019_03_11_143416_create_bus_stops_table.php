<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_stops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');

            $table->integer('stop_code');
            $table->integer('common_code');

            $table->integer('road_side_id');
            $table->foreign('road_side_id')->references('id')->on('road_sides');

            $table->string('road');
            $table->string('street');

            $table->integer('region_id');
            $table->foreign('region_id')->references('id')->on('regions');

            $table->integer('parish_id');
            $table->foreign('parish_id')->references('id')->on('parishes');

            $table->integer('village_id');
            $table->foreign('village_id')->references('id')->on('villages');

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
        Schema::dropIfExists('bus_stops');
    }
}
