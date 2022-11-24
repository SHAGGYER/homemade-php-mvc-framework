<?php

namespace App\Traits;

use App\Helpers\Helpers;
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

        return $token;
    }
}