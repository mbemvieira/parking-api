<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry', 'exit'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function parking_place()
    {
        return $this->belongsTo('App\ParkingPlace');
    }
}
