<?php

namespace App\Models;

use App\Lib\Model;
use App\Traits\ConvertsModelToArray;
use App\Traits\HasApiTokens;
use stdClass;

class User extends Model {
    use HasApiTokens;

    public string $table = "users";
    
    public static function emailExists(string $email) {
        $user = static::where([
            ["email", "=", $email]
        ])->first();
        return $user ? true : false;
    }
}