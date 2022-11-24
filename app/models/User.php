<?php

namespace App\Models;

use App\Lib\Model;
use App\Traits\HasApiTokens;

class User extends Model {
    use HasApiTokens;

    public string $table = "users";
    public array $hidden = ["password"];
    
    public static function emailExists(string $email) {
        $user = static::where([
            ["email", "=", $email]
        ])->first();
        return $user ? true : false;
    }

    public function token() {
        return $this->hasOne(Token::class, "user_id", "id");
    }

    public function roles() {
        return $this->belongsToMany(Role::class, "user_roles", "user_id", "role_id");
    }
}