<?php


namespace App\Lib;

use App\Exceptions\InvalidSignatureException;
use App\Helpers\Helpers;
use App\Models\Token;
use App\Models\User;
use Exception;

class Authentication
{
    private static User $user;

    public static function id(): int
    {
        return self::$user->id;
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = User::where([
            ["email", "=", $email]
        ])->first();

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            self::$user = $user;
            return true;
        }

        return false;
    }

    public static function login(User $user = null)
    {
        self::$user = $user;
        return $user;
    }

    public static function getUser(): ?User
    {
        if (isset(self::$user)) {
            return self::$user;
        }

        return null;
    }

    public static function newSession(): ?User
    {
        $user = self::newSessionFromToken(Helpers::getBearerToken());
        return $user;
    }

    public static function newSessionFromToken(?string $token): ?User
    {
        if (!$token) {
            throw new InvalidSignatureException("Invalid token (no token)");
        }

        try {
            $codec = new JWTCodec();
            $payload = $codec->decode($token);
            if (!$payload["user_id"]) {
                return null;
            }

            $signature = hash_hmac("sha256", $token, Config::get("JWT_SECRET"));
            $tokenModel = Token::where([
                ["token", "=", $signature]
            ])->first();
            if (!$tokenModel) {
                return null;
            }

            $user = User::with(["roles"])->where([
                ["id", "=", $payload["user_id"]]
            ])->first();

            return $user;
        } catch (\Exception $e) {
            return Response::json(["message" => $e->getMessage()], 401);
        }
    }
}
