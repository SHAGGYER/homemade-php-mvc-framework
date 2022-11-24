<?php

namespace App\Traits;

use App\Helpers\Helpers;
use App\Lib\Config;
use App\Lib\JWTCodec;
use App\Models\RefreshToken;
use App\Models\Token;

trait HasApiTokens {
    public function getTokenPayload(): array {
        $token = Helpers::getBearerToken();
        $codec = new JWTCodec();
        $payload = $codec->decode($token);
        return $payload;
    }

    public function createToken(): string {
        $codec = new JWTCodec();
        $token = $codec->encode([
            "user_id" => $this->id,
            "exp" => time() + 60 * 60 * 24
        ]);

        $existingTokenModel = Token::where([
            ["user_id", "=", $this->id]
        ])->first();
        if ($existingTokenModel) {
            Token::delete()->where([
                ["user_id", "=", $this->id]
            ])->execute();
        }

        $tokenModel = new Token();
        $tokenModel->user_id = $this->id;
        $tokenModel->token = hash_hmac("sha256", $token, Config::get("JWT_SECRET"));;
        $tokenModel->save();

        return $token;
    }
}