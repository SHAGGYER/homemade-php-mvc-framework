<?php

namespace App\Traits;

use App\Helpers\Helpers;
use App\Models\Token;

trait HasApiTokens {
    public function createToken() {
        $existing_token = Token::query()->where([
            ["user_id", "=", $this->id]
        ])->first();
        if (!$existing_token) {
            $token = bin2hex(random_bytes(32));
            $this->token = $token;
            $this->save();
            return $token;
        } else {
            $this->token = $existing_token->token;
            return $this->token;
        }
    }

    public function getToken() {
        return $this->token;
    }

    public function getBearerToken() {
        $token = Helpers::getBearerToken();
    } 
}