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
            http_response_code(401);
            echo(json_encode(["message" => "You are not logged in!"]));
            exit;
            // @todo Make a json response
        }
    }

    
}