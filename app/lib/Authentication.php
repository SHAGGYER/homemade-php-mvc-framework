<?php


namespace App\Lib;

use App\Helpers\Helpers;
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

    public static function getUser(): ?User
    {
        $token = Authentication::getToken(Helpers::getBearerToken());
        if ($token->id) {
            $user = User::query()->where([
                ["id", "=", $token->user_id]
            ])->first();
            return $user;
        }

        return null;
    }
}