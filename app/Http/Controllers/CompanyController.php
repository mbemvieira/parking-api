<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use Illuminate\Http\Request;
use Validator;

class CompanyController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show()
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
        $response['company'] = $company;

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'company_name'      => 'required|string|max:100',
            'cnpj'              => 'required|unique:companies,cnpj,'. $company->cnpj .',cnpj|digits_between:1,20',
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
            $response['status'] = -1;
            $response['message'] = 'Validation failed!';
            $response['validator'] = $validator->errors();

            return response()
                ->json($response, 200,
                    ['Content-type' => 'application/json; charset=utf-8'],
                    JSON_UNESCAPED_UNICODE);
        }

        $company->company_name = $request->has('company_name') ? $request->input('company_name') : null;
        $company->cnpj = $request->has('cnpj') ? $request->input('cnpj') : null;
        $company->phone = $request->has('phone') ? $request->input('phone') : null;

        $company->zip_code = $request->has('zip_code') ? $request->input('zip_code') : null;
        $company->address_street = $request->has('address_street') ? $request->input('address_street') : null;
        $company->address_number = $request->has('address_number') ? $request->input('address_number') : null;
        $company->address_neighbour = $request->has('address_neighbour') ? $request->input('address_neighbour') : null;
        $company->address_city = $request->has('address_city') ? $request->input('address_city') : null;
        $company->address_state = $request->has('address_state') ? $request->input('address_state') : null;
        $company->address_country = $request->has('address_country') ? $request->input('address_country') : null;

        $company->save();

        $response['status'] = 0;
        $response['message'] = 'Ok!';

        return response()
            ->json($response, 200,
                ['Content-type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE);
    }
}
