<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1.0', 'middleware' => 'auth.basic'], function () {
    // COMPANY
    Route::get('company', 'CompanyController@show')->name('company.show');
    Route::patch('company/{company}', 'CompanyController@update')->name('company.update');


    // CLIENT
    Route::get('client', 'ClientController@index')->name('client.index');
    Route::post('client', 'ClientController@store')->name('client.store');
    Route::get('client/{client}', 'ClientController@show')->name('client.show');
    Route::patch('client/{client}', 'ClientController@update')->name('client.update');
    Route::delete('client/{client}', 'ClientController@destroy')->name('client.destroy');


    // VEHICLE
    Route::get('vehicle', 'VehicleController@index')->name('vehicle.index');
    Route::post('vehicle', 'VehicleController@store')->name('vehicle.store');
    Route::get('vehicle/{vehicle}', 'VehicleController@show')->name('vehicle.show');
    Route::patch('vehicle/{vehicle}', 'VehicleController@update')->name('vehicle.update');
    Route::delete('vehicle/{vehicle}', 'VehicleController@destroy')->name('vehicle.destroy');


    // PARKING PLACE
    Route::get('parking-place', 'ParkingPlaceController@index')->name('parking-place.index');


    // PAYMENT
    Route::get('payment', 'PaymentController@index')->name('payment.index');

});
