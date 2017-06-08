<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plate', 'brand', 'model',
        'color', 'year'
    ];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function parking_place()
    {
        return $this->belongsTo('App\ParkingPlace');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}
