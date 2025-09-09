<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookCredential extends Model
{
    protected $table = 'facebook_credentials';
    protected $guarded = [];

    protected $hidden = [
        'app_secret',
        'user_access_token',
    ];

    protected $casts = [
        'app_secret' => 'encrypted',
        'user_access_token' => 'encrypted',
    ];
}
