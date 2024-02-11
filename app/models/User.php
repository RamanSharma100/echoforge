<?php

namespace App\Models;

use Forge\core\Model;

class User extends Model
{
    protected $fillable = [
        "email",
        "password",
    ];

    protected $gaurded = [
        'password'
    ];
}
