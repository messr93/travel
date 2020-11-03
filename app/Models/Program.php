<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';
    /*protected $fillable = ['trip_id', 'admin_id', 'status'];*/
    protected $fillable = ['photo', 'status'];
    protected $hidden = ['updated_at', 'created_at'];
    public $timestamps = true;

    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    /*public function trip(){
        return $this->belongsTo('App\Models\Trip', 'trip_id', 'id');
    }

    public function admin(){
        return $this->belongsTo('App\Models\Admin', 'admin_id', 'id');
    }*/

    public function programContents(){
        return $this->hasMany('App\Models\ProgramContent', 'program_id', 'id');
    }

    public function offers(){
        return $this->hasMany('App\Models\Offer', 'program_id', 'id');
    }


    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////

    /////////////////////////////////////// Begin Mutators /////////////////////////////////////////////

    /*public function getStatusAttribute($status){
        return $status == 1? 'Active': 'unActive';
    }*/

    public function setAdminIdAttribute($value){

    }
    /////////////////////////////////////// End Mutators /////////////////////////////////////////////

    ///////////////////////////////////////  Begin Scopes  /////////////////////////////////////////////




    ///////////////////////////////////////  End Scopes  /////////////////////////////////////////////
}
