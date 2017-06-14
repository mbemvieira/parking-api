<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\ParkingPlace;
use App\User;
use App\Vehicle;
use Illuminate\Http\Request;
use Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::first();
        $company = Company::where('user_id', $user->id)->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -1;
            $response['message'] = 'Company not found!';
            return $response;
        } else if ( $company->clients->all() === [] ) {
            $response['status'] = -2;
            $response['message'] = 'No clients registered!';
            return $response;
        }

        $clients_id = $company->clients()->pluck('id')->all();

        $vehicles = Vehicle::whereIn('client_id', $clients_id)->get();

        $response['status'] = 0;
        $response['message'] = 'Ok!';
        $response['clients'] = $vehicles;

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plate'     => 'required|string|max:50',
            'brand'     => 'required|string|max:50',
            'model'     => 'required|string|max:50',
            'color'     => 'string|max:50',
            'year'      => 'numeric|digits_between:1,20',
            'client_id' => 'required|numeric|digits_between:1,20',
            'parking_place_id' => 'numeric|digits_between:1,20',
        ]);

        $response = [];

        if($validator->fails()) {
            $response['status'] = -3;
            $response['message'] = 'Validation failed!';
            $response['validator'] = $validator->errors();

            return response()
                ->json($response, 200,
                    ['Content-type' => 'application/json; charset=utf-8'],
                    JSON_UNESCAPED_UNICODE);
        }

        $user = User::first();

        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($request) {
                $query->where('id', $request->has('client_id') ? $request->input('client_id') : 0);
            })->whereHas('parking_places', function ($query) use ($request) {
                if ( $request->has('parking_place_id') ) {
                    $query->where('id', $request->input('parking_place_id'));
                }
            })
            ->first();

        if ( $company === null ) {
            $response['status'] = -2;
            $response['message'] = 'Client or Parking Place not found!';
            return $response;
        }

        $new_plate = $request->has('plate') ? $request->input('plate') : 0;

        $client = Client::where('id', $request->has('client_id') ? $request->input('client_id') : 0)
            ->whereHas('vehicles', function ($query) use ($new_plate) {
                $query->where('plate', $new_plate);
            })->first();

        if ( $client !== null ) {
            $response['status'] = -1;
            $response['message'] = 'This Client already has a Vehicle with this Plate!';
            return $response;
        }

        $vehicle = new Vehicle;

        $vehicle->plate = $request->has('plate') ? $request->input('plate') : null;
        $vehicle->brand = $request->has('brand') ? $request->input('brand') : null;
        $vehicle->model = $request->has('model') ? $request->input('model') : null;
        $vehicle->color = $request->has('color') ? $request->input('color') : null;
        $vehicle->year = $request->has('year') ? $request->input('year') : null;
        $vehicle->is_parked = $request->has('parking_place_id') ? true : false;
        $vehicle->client_id = $request->has('client_id') ? $request->input('client_id') : null;
        $vehicle->parking_place_id = $request->has('parking_place_id') ?
            $request->input('parking_place_id') : null;

        $vehicle->save();

        // Use disassociate first
        if ( $request->has('parking_place_id') ) {
            $parking_place = ParkingPlace::find( $request->input('parking_place_id') );
            $parking_place->is_empty = false;
            $parking_place->save();
        }

        $response['status'] = 0;
        $response['message'] = 'Ok!';

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        $user = User::first();
        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($vehicle) {
                $query->where('id', $vehicle->client->id);
            })->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -1;
            $response['message'] = 'Vehicle not found!';
            return $response;
        }

        $response['status'] = 0;
        $response['message'] = 'Ok!';
        // TODO: Dont send client
        $response['vehicle'] = $vehicle;

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(), [
            'plate'     => 'required|string|max:50',
            'brand'     => 'required|string|max:50',
            'model'     => 'required|string|max:50',
            'color'     => 'string|max:50',
            'year'      => 'numeric|digits_between:1,20',
            'client_id' => 'required|numeric|digits_between:1,20',
            'parking_place_id' => 'numeric|digits_between:1,20',
        ]);

        $response = [];

        if($validator->fails()) {
            $response['status'] = -3;
            $response['message'] = 'Validation failed!';
            $response['validator'] = $validator->errors();

            return response()
                ->json($response, 200,
                    ['Content-type' => 'application/json; charset=utf-8'],
                    JSON_UNESCAPED_UNICODE);
        }

        $user = User::first();

        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($vehicle) {
                $query->where('id', $vehicle->client->id);
            })->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -3;
            $response['message'] = 'Vehicle not found!';
            return $response;
        }

        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($request) {
                $query->where('id', $request->has('client_id') ? $request->input('client_id') : 0);
            })->whereHas('parking_places', function ($query) use ($request) {
                if ( $request->has('parking_place_id') ) {
                    $query->where('id', $request->input('parking_place_id'));
                }
            })
            ->first();

        if ( $company === null ) {
            $response['status'] = -2;
            $response['message'] = 'Client or Parking Place not found!';
            return $response;
        }

        $new_plate = $request->has('plate') ? $request->input('plate') : 0;

        $client = Client::where('id', $request->has('client_id') ? $request->input('client_id') : 0)
            ->whereHas('vehicles', function ($query) use ($new_plate) {
                $query->where('plate', $new_plate);
            })->first();

        if ( ($client !== null) && ($new_plate != $vehicle->plate) ) {
            $response['status'] = -1;
            $response['message'] = 'This Client already has a Vehicle with this Plate!';
            return $response;
        }

        $vehicle->plate = $request->has('plate') ? $request->input('plate') : null;
        $vehicle->brand = $request->has('brand') ? $request->input('brand') : null;
        $vehicle->model = $request->has('model') ? $request->input('model') : null;
        $vehicle->color = $request->has('color') ? $request->input('color') : null;
        $vehicle->year = $request->has('year') ? $request->input('year') : null;
        $vehicle->is_parked = $request->has('parking_place_id') ? true : false;
        $vehicle->client_id = $request->has('client_id') ? $request->input('client_id') : null;
        $vehicle->parking_place_id = $request->has('parking_place_id') ?
            $request->input('parking_place_id') : null;

        $vehicle->save();

        if ( $request->has('parking_place_id') ) {
            $parking_place = ParkingPlace::find( $request->input('parking_place_id') );
            $parking_place->is_empty = false;
            $parking_place->save();
        }

        $response['status'] = 0;
        $response['message'] = 'Ok!';

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $user = User::first();
        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($vehicle) {
                $query->where('id', $vehicle->client->id);
            })->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -1;
            $response['message'] = 'Vehicle not found!';
            return $response;
        }

        $vehicle->delete();

        $response['status'] = 0;
        $response['message'] = 'Ok!';

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }
}
