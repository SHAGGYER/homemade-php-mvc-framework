<?php


namespace App\Lib;

use App\Models\Token;
use App\Models\User;

class Authentication {
    private static User $user;

    public static function getToken($token): Token {
        $token = Token::query()->where([
            ["token", "=", $token]
        ])->first();
        return $token;
    }

    public static function login(User $user = null) {
        self::$user = $user;
    }

    public static function getUser(): User
    {
        return self::$user;
    }
}