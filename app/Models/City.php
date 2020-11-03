<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['name_ar', 'name_en'];
    public $timestamps = false;

    ///////////////////////////////////////////////////// Begin  relations ///////////////////////////////////////

    public function regions(){
        return $this->hasMany('App\Models\Region', 'city_id', 'id');
    }

    public function offers(){
        return $this->hasMany('App\Models\Offer', 'city_id', 'id');
    }

    ///////////////////////////////////////////////////// End  relations ///////////////////////////////////////
}
