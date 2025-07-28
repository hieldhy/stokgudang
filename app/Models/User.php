<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'userid';

    protected $fillable = [
        'username',
        'password',
        'session',
    ];

    protected $hidden = [
        'password',
        'session',
        'remember_token',
    ];

    public $timestamps = true;
}