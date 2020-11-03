<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferPhoto extends Model
{
    protected $table = 'offer_photos';
    protected $fillable = ['offer_id', 'photo'];
    protected $hidden = ['created_at', 'updated_at'];

    ////////////////////////////////////////////////////  Begin Relations //////////////////////////////////////////////////

    public function offer(){
        return $this->belongsTo('App\Models\Offer', 'offer_id', 'id');
    }

    ////////////////////////////////////////////////////  End Relations //////////////////////////////////////////////////
}
