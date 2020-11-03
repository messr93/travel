<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $fillable = ['city_id', 'name_ar', 'name_en'];
    public $timestamps = false;

    ///////////////////////////////////////////////////// Begin  relations ///////////////////////////////////////

    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }

    public function offers(){
        return $this->hasMany('App\Models\Offer', 'region_id', 'id');
    }

    ///////////////////////////////////////////////////// End  relations ///////////////////////////////////////
}
