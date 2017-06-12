<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use App\ParkingPlace;
use Illuminate\Http\Request;

class ParkingPlaceController extends Controller
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
        }

        $response['status'] = 0;
        $response['message'] = 'Ok!';
        $response['clients'] = $company->parking_places->all();

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ParkingPlace  $parkingPlace
     * @return \Illuminate\Http\Response
     */
    public function show(ParkingPlace $parkingPlace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ParkingPlace  $parkingPlace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParkingPlace $parkingPlace)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ParkingPlace  $parkingPlace
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParkingPlace $parkingPlace)
    {
        //
    }
}
