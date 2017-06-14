<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api/v1.0'], function () {
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


    // PARKING PLACE
    Route::get('parking-place', 'ParkingPlaceController@index')->name('parking-place.index');


    // PAYMENT
    Route::get('payment', 'PaymentController@index')->name('payment.index');

});
