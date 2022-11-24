<?php

namespace App\Traits;

use App\Helpers\Helpers;
use App\Models\Token;

trait HasApiTokens {
    public function createToken(int $user_id) {
        $existing_token = Token::where([
            ["user_id", "=", $this->id]
        ])->first();

        if (!$existing_token) {
            $tokenStr = bin2hex(random_bytes(32));
            
            $token = new Token();
            $token->user_id = $user_id;
            $token->token = $tokenStr;
            $token->save();
            return $token->token;
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