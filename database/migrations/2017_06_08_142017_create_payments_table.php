<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->integer('parking_place_id')->unsigned();
            $table->foreign('parking_place_id')->references('id')->on('parking_places');
            
            $table->dateTime('entry');
            $table->dateTime('exit');

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
        Schema::dropIfExists('payments');
    }
}
