<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('client_name');
            $table->string('cpf');
            $table->unique(['cpf', 'company_id']);
            $table->string('phone');

            $table->string('zip_code')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_neighbour')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_country')->nullable();

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
        Schema::dropIfExists('clients');
    }
}
