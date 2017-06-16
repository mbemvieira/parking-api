<?php

use Illuminate\Database\Seeder;

class ParkingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Remove old data
        App\Payment::truncate();
        App\Vehicle::truncate();
        App\Client::truncate();
        App\ParkingPlace::truncate();
        App\Company::truncate();

        $users = App\User::all();

        // Use even numbers
        $quantity = 2;

        foreach ($users as $user) {
            $user->company()->save( factory(App\Company::class, 1)->make()->first() );

            $parking_places = factory(App\ParkingPlace::class, $quantity)->create([
                'company_id' => $user->company->id
            ]);

            $clients = factory(App\Client::class, $quantity)->create([
                'company_id' => $user->company->id
            ])->each(function ($client) use ($parking_places) {
                $client->vehicles()->saveMany( factory(App\Vehicle::class, $quantity / 2)->make() );

                foreach ($client->vehicles as $vehicle) {
                    foreach ($parking_places as $parking_place) {
                        factory(App\Payment::class, $quantity * 2)->create([
                            'vehicle_id' => $vehicle->id,
                            'parking_place_id' => $parking_place->id
                        ]);
                    }
                }
            });
        }

        // $companies
    }
}
