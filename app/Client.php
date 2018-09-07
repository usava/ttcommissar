<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo('App\City', 'cityId', 'id');
    }

    public function coordinates()
    {
        return $this->hasMany(ClientCoordinate::class);
    }

}
