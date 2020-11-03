<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';
    protected $fillable = ['program_id', 'user_id', 'city_id', 'region_id', 'status', 'lat', 'lng'];
    protected $hidden = [ 'created_at'];
    public $timestamps = true;

    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    public function program(){
        return $this->belongsTo('App\Models\Program', 'program_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }


    public function offerContents(){
        return $this->hasMany('App\Models\OfferContent', 'offer_id', 'id');
    }

    public function photos(){
        return $this->hasMany('App\Models\OfferPhoto', 'offer_id', 'id');
    }

    public function prices(){
        return $this->hasMany('App\Models\OfferPrice', 'offer_id', 'id');
    }

    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id', 'id');
    }

    public function region(){
        return $this->belongsTo('App\Models\Region', 'region_id', 'id');
    }

                    /// get desired content directly ////
    public function content(){
        return $this->offerContents->sortBy('lang_id')->last();
    }


    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////

    /////////////////////////////////////// Begin Mutators /////////////////////////////////////////////

    /*public function getStatusAttribute($status){
        return $status == 1? 'Active': 'unActive';
    }*/

    public function setAdminIdAttribute($value){

    }
    /////////////////////////////////////// End Mutators /////////////////////////////////////////////
}
