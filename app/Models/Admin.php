<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $fillable = ['name', 'email', 'password', 'image'];
    protected $hidden = ['password', 'remember_token', 'updated_at', 'created_at'];
    protected $casts = ['email_verified_at' => 'datetime'];

    ///////////////////////////////////////  Begin Relations  /////////////////////////////////////////////

    ///////////////////////////////////////  End Relations  /////////////////////////////////////////////
}
