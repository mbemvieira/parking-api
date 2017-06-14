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
}
