<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParkingPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parking_places', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('vehicle_id')->unsigned()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->string('parking_place_name');
            $table->unique(['parking_place_name', 'company_id']);
            $table->dateTime('vehicle_last_entry')->nullable();
            $table->boolean('is_empty');

            $table->timestamps();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('parking_place_id')->references('id')->on('parking_places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['parking_place_id']);
        });
        
        Schema::dropIfExists('parking_places');
    }
}
