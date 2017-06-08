<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParkingPlace extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parking_place_name'
    ];

    public function company()
    {
        return $this->belongsTo('App\ParkingPlace');
    }

    public function vehicle()
    {
        return $this->hasOne('App\Vehicle');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}
