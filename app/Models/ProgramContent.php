<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramContent extends Model
{
    protected $table = 'program_contents';
    protected $fillable = ['program_id', 'lang_id', 'name', 'slug', 'description'];
    protected $hidden = ['updated_at', 'created_at'];
    public $timestamps = true;

    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    public function program(){
        return $this->belongsTo('App\Models\Program', 'program_id', 'id');
    }

    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////
}
