<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    protected $table = 'langs';
    protected $fillable = ['code', 'name', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

}
