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

            $table->integer('stop_code')->index();
            $table->integer('common_code')->index()->nullable();

            $table->double('position_X');
            $table->double('position_Y');

            $table-> unsignedBigInteger('road_side_id')->index();
            $table->foreign('road_side_id')->references('id')->on('road_sides');

            $table->string('road')->nullable();
            $table->string('street')->nullable();

            $table->unsignedBigInteger('region_id')->nullable()->index();
            $table->foreign('region_id')->references('id')->on('regions');

            $table->unsignedBigInteger('parish_id')->nullable()->index();
            $table->foreign('parish_id')->references('id')->on('parishes');

            $table->unsignedBigInteger('village_id')->nullable()->index();
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
