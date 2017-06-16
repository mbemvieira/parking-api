<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Company::class, function (Faker\Generator $faker) {
    $localisedFaker = Faker\Factory::create("pt_BR");

    return [
        // user_id
        'company_name' => $localisedFaker->company,
        'cnpj' => $localisedFaker->unique()->cnpj(false),
        'phone' => $localisedFaker->phoneNumberCleared,

        'zip_code' => $localisedFaker->numerify('########'),
        'address_street' => $localisedFaker->streetName,
        'address_number' => $localisedFaker->numerify('##'),
        'address_neighbour' => 'Centro',
        'address_city' => $localisedFaker->city,
        'address_state' => $localisedFaker->state,
        'address_country' => 'Brasil',
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Client::class, function (Faker\Generator $faker) {
    $localisedFaker = Faker\Factory::create("pt_BR");

    return [
        // company_id
        'client_name' => $localisedFaker->name,
        'cpf' => $localisedFaker->unique()->cpf(false),
        'phone' => $localisedFaker->phoneNumberCleared,

        'zip_code' => $localisedFaker->numerify('########'),
        'address_street' => $localisedFaker->streetName,
        'address_number' => $localisedFaker->numerify('##'),
        'address_neighbour' => 'Centro',
        'address_city' => $localisedFaker->city,
        'address_state' => $localisedFaker->state,
        'address_country' => 'Brasil',
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\ParkingPlace::class, function (Faker\Generator $faker) {
    return [
        // company_id
        'parking_place_name' => $faker->unique()->bothify('?##'),
        'vehicle_last_entry' => \Carbon\Carbon::now(),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Vehicle::class, function (Faker\Generator $faker) {
    return [
        // client_id, parking_place_id
        'plate' => $faker->unique()->bothify('???####'),
        'brand' => $faker->word,
        'model' => $faker->word,
        'color' => $faker->safeColorName,
        'year' => $faker->numerify('200#'),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Payment::class, function (Faker\Generator $faker) {
    return [
        // vehicle_id, parking_place_id
        'entry' => $faker->dateTimeBetween('-30 days', '-25 days'),
        'exit' => $faker->dateTimeBetween('-26 days', '-20 days'),
    ];
});
