<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name', 'cpf', 'phone',
        'zip_code', 'address_street', 'address_number',
        'address_neighbour', 'address_city', 'address_state',
        'address_country'
    ];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Vehicle');
    }
}
