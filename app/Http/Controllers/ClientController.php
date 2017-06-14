<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\User;
use Illuminate\Http\Request;
use Validator;

class ClientController extends Controller
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
        $response['clients'] = $company->clients->all();

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
            'client_name'       => 'required|string|max:100',
            'cpf'               => 'required|digits_between:1,20',
            'phone'             => 'required|digits_between:1,20',
            'zip_code'          => 'numeric|digits_between:1,20',
            'address_street'    => 'string|max:100',
            'address_number'    => 'numeric|digits_between:1,20',
            'address_city'      => 'string|max:50',
            'address_state'     => 'string|max:50',
            'address_country'   => 'string|max:50',
        ]);

        $response = [];

        if($validator->fails()) {
            $response['status'] = -2;
            $response['message'] = 'Validation failed!';
            $response['validator'] = $validator->errors();

            return response()
                ->json($response, 200,
                    ['Content-type' => 'application/json; charset=utf-8'],
                    JSON_UNESCAPED_UNICODE);
        }

        $user = User::first();

        $new_cpf = $request->has('cpf') ? $request->input('cpf') : 0;

        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($new_cpf) {
                $query->where('cpf', $new_cpf);
            })->first();

        if ( $company !== null ) {
            $response['status'] = -1;
            $response['message'] = 'There is already a Client with this CPF!';
            return $response;
        }

        $company = Company::where('user_id', $user->id)->first();

        $client = new Client;

        $client->client_name = $request->has('client_name') ? $request->input('client_name') : null;
        $client->cpf = $request->has('cpf') ? $request->input('cpf') : null;
        $client->phone = $request->has('phone') ? $request->input('phone') : null;

        $client->zip_code = $request->has('zip_code') ? $request->input('zip_code') : null;
        $client->address_street = $request->has('address_street') ? $request->input('address_street') : null;
        $client->address_number = $request->has('address_number') ? $request->input('address_number') : null;
        $client->address_neighbour = $request->has('address_neighbour') ? $request->input('address_neighbour') : null;
        $client->address_city = $request->has('address_city') ? $request->input('address_city') : null;
        $client->address_state = $request->has('address_state') ? $request->input('address_state') : null;
        $client->address_country = $request->has('address_country') ? $request->input('address_country') : null;

        $client->company()->associate($company);
        $client->save();

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
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $user = User::first();
        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($client) {
                $query->where('id', $client->id);
            })->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -1;
            $response['message'] = 'Client not found!';
            return $response;
        }

        $response['status'] = 0;
        $response['message'] = 'Ok!';
        $response['client'] = $client;

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'client_name'      => 'required|string|max:100',
            'cpf'              => 'required|digits_between:1,20',
            'phone'             => 'required|digits_between:1,20',
            'zip_code'          => 'numeric|digits_between:1,20',
            'address_street'    => 'string|max:100',
            'address_number'    => 'numeric|digits_between:1,20',
            'address_city'      => 'string|max:50',
            'address_state'     => 'string|max:50',
            'address_country'   => 'string|max:50',
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
            ->whereHas('clients', function ($query) use ($client) {
                $query->where('id', $client->id);
            })->first();

        if ( $company === null ) {
            $response['status'] = -2;
            $response['message'] = 'Client not found!';
            return $response;
        }

        $new_cpf = $request->has('cpf') ? $request->input('cpf') : 0;

        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($new_cpf) {
                $query->where('cpf', $new_cpf);
            })->first();

        if ( ($company !== null) && ($new_cpf != $client->cpf) ) {
            $response['status'] = -1;
            $response['message'] = 'There is already a Client with this CPF!';
            return $response;
        }

        $company = Company::where('user_id', $user->id)->first();

        $client->client_name = $request->has('client_name') ? $request->input('client_name') : null;
        $client->cpf = $request->has('cpf') ? $request->input('cpf') : null;
        $client->phone = $request->has('phone') ? $request->input('phone') : null;

        $client->zip_code = $request->has('zip_code') ? $request->input('zip_code') : null;
        $client->address_street = $request->has('address_street') ? $request->input('address_street') : null;
        $client->address_number = $request->has('address_number') ? $request->input('address_number') : null;
        $client->address_neighbour = $request->has('address_neighbour') ? $request->input('address_neighbour') : null;
        $client->address_city = $request->has('address_city') ? $request->input('address_city') : null;
        $client->address_state = $request->has('address_state') ? $request->input('address_state') : null;
        $client->address_country = $request->has('address_country') ? $request->input('address_country') : null;

        $client->save();

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
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $user = User::first();
        $company = Company::where('user_id', $user->id)
            ->whereHas('clients', function ($query) use ($client) {
                $query->where('id', $client->id);
            })->first();

        $response = [];

        if ( $company === null ) {
            $response['status'] = -1;
            $response['message'] = 'Client not found!';
            return $response;
        }

        $client->delete();

        $response['status'] = 0;
        $response['message'] = 'Ok!';

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }
}
