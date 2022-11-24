<?php


namespace App\Lib;

use App\Helpers\Helpers;
use App\Models\Token;
use App\Models\User;

class Authentication {
    private static User $user;

    public static function id(): int {
        return self::$user->id;
    }

    public static function attempt(string $email, string $password): bool {
        $user = User::where([
            ["email", "=", $email]
        ])->first();

        if ($user) {
            if (password_verify($password, $user->password)) {
                self::$user = $user;
                return true;
            }
        }

        return false;
    }

    public static function getToken(?string $token = null): ?Token {
        $token = Token::where([
            ["token", "=", Helpers::getBearerToken()]
        ])->first();
        return $token;
    }

    public static function login(User $user = null) {
        self::$user = $user;
        return $user;
    }

    public static function getUser(): ?User
    {
        if (isset(self::$user)) {
            return self::$user;
        }

        $token = Authentication::getToken();
        if (! empty($token) && $token->id) {
            $user = User::where([
                ["id", "=", $token->user_id]
            ])->first();
            self::$user = $user;
            return $user;
        }

        return null;
    }
}