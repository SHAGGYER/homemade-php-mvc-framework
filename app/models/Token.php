<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Lib\Model;

class Token extends Model {
    protected string $table = "tokens";
    
    public function createToken(int $user_id) {
        $existing_token = Token::query()->where([
            ["user_id", "=", $user_id]
        ])->first();
        if (!$existing_token) {
            $token = bin2hex(random_bytes(32));
            $this->token = $token;
            $this->user_id = $user_id;
            $this->save();
            return $token;
        } else {
            $this->token = $existing_token->token;
            $this->user_id = $user_id;
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