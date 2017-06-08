<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name', 'cnpj', 'phone',
        'zip_code', 'address_street', 'address_number',
        'address_neighbour', 'address_city', 'address_state',
        'address_country'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    public function parking_places()
    {
        return $this->hasMany('App\ParkingPlace');
    }
}
