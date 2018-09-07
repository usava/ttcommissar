<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo('App\Country', 'countryId', 'id');
    }
}
