<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferPrice extends Model
{
    protected $table = 'offer_prices';
    protected $fillable = ['offer_id', 'price', 'hint'];
    protected $hidden = ['created_at', 'updated_at'];

    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    public function offer(){
        return $this->belongsTo('App\Models\Offer', 'offer_id', 'id');
    }


    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////
}
