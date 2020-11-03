<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripContent extends Model
{
    protected $table = 'trip_contents';
    protected $fillable = ['trip_id', 'lang', 'name', 'description', 'status'];
    protected $hidden = ['updated_at', 'created_at'];
    public $timestamps = true;


    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    public function trip(){
        return $this->belongsTo('App\Models\Trip', 'trip_id', 'id');
    }


    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////
}
