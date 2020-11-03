<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['type', 'notifiable_type', 'notifiable_id', 'data', 'read_at'];
    public $timestamps = true;
}
