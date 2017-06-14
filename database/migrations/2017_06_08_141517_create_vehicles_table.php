<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('parking_place_id')->unsigned()->nullable();
            $table->foreign('parking_place_id')->references('id')->on('parking_places');

            $table->string('plate');
            $table->unique(['plate', 'client_id']);
            $table->string('brand');
            $table->string('model');
            $table->string('color')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_parked');

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
        Schema::dropIfExists('vehicles');
    }
}
