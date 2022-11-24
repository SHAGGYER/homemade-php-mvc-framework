<?php

namespace App\Models;

use App\Lib\Config;
use App\Lib\Model;

class RefreshToken extends Model {
    public string $table = "refresh_tokens";
    public bool $timestamps = false;

    public static function create(string $tokenStr, int $expiry) {
        $token = new RefreshToken();
        $token->token_hash = hash_hmac("sha256", $tokenStr, Config::get("JWT_SECRET"));
        $token->expires_at = $expiry;
        $token->save();
        return $token;
    }

    public static function deleteToken(string $tokenStr) {
        $token_hash = hash_hmac("sha256", $tokenStr, Config::get("JWT_SECRET"));
        RefreshToken::delete()->where([
            ["token_hash", "=", $token_hash]
        ])->execute();
    }
    
}