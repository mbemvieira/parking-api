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

// COMPANY
Route::get('api/v1.0/company', 'CompanyController@show')->name('company.show');


// CLIENT
Route::get('api/v1.0/client', 'ClientController@index')->name('client.index');


// VEHICLE
Route::get('api/v1.0/vehicle', 'VehicleController@index')->name('vehicle.index');


// PARKING PLACE
Route::get('api/v1.0/parking-place', 'ParkingPlaceController@index')->name('parking-place.index');


// PAYMENT
Route::get('api/v1.0/payment', 'PaymentController@index')->name('payment.index');
