<?php

namespace App\Middleware;

use App\Helpers\Helpers;
use App\Lib\Authentication;
use App\Models\User;

class AuthenticateMiddleware {
    public function handle() {
        if ($token = Helpers::getBearerToken()) {
            $dbToken = Authentication::getToken($token);
            if ($dbToken) {
                Authentication::login(User::query()->where([
                    ["id", "=", $dbToken->user_id]
                ])->first());
            }
        } else {
            echo("You are not logged in!");
            // @todo Make a json response
        }
    }

    
}