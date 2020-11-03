<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $table = 'trips';
    protected $fillable = ['admin_id', 'photo', 'status'];
    protected $hidden = ['updated_at', 'created_at'];
    public $timestamps = true;


    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    public function admin(){
        return $this->belongsTo('App\Models\Admin', 'admin_id', 'id');
    }


    public function tripsContent(){
        return $this->hasMany('App\Models\TripContent', 'trip_id', 'id');
    }

    public function programs(){
        return $this->hasMany('App\Models\Program', 'trip_id', 'id');
    }

    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////
}
