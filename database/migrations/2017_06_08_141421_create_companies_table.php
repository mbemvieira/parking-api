<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('company_name');
            $table->string('cnpj')->unique();
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
        Schema::dropIfExists('companies');
    }
}
