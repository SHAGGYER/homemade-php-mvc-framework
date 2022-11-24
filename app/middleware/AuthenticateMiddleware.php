<?php

namespace App\Middleware;

use App\Helpers\Helpers;
use App\Lib\Authentication;
use App\Lib\Response;
use App\Models\User;

class AuthenticateMiddleware {
    public function handle() {
        if ($token = Helpers::getBearerToken()) {
            Authentication::newSessionFromToken($token);
        } else {
            Response::json(["message" => "Unauthorized"], 401);
            exit;
        }
    }
}