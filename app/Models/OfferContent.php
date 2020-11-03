<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferContent extends Model
{
    protected $table = 'offer_contents';
    protected $fillable = ['offer_id', 'lang_id', 'name', 'slug', 'description', 'address'];
    protected $hidden = ['updated_at', 'created_at'];
    public $timestamps = true;

    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    public function offer(){
        return $this->belongsTo('App\Models\Offer', 'offer_id', 'id');
    }

    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////
}
