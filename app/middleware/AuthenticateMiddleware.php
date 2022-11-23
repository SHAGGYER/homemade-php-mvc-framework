<?php

namespace App\Middleware;

use App\Lib\Authentication;

class AuthenticateMiddleware {
    public function handle() {
        if (isset($_SESSION["user"])) {
            Authentication::login();
        } else {
            exit("You are not logged in!");
            // @todo Make a json response
        }
    }
}