<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Joshwhatk\Cent\UserModel;

class User extends UserModel
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
         'email',
         'password',
         'name',
         'permissions',
     ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
