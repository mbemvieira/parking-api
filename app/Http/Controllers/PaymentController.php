<?php

namespace App\Http\Controllers;

use App\Company;
use App\Payment;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
        } else if ( $company->parking_places->all() === [] ) {
            $response['status'] = -2;
            $response['message'] = 'No parking places registered!';
            return $response;
        }

        $parking_places_id = $company->parking_places()->pluck('id')->all();

        $payments = Payment::whereIn('parking_place_id', $parking_places_id)->get();

        $response['status'] = 0;
        $response['message'] = 'Ok!';
        $response['clients'] = $payments;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
