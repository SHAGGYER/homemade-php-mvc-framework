<?php

namespace App\Controllers;

use App\Helpers\Helpers;
use App\Lib\Authentication;
use App\Lib\Controller;
use App\Lib\JWTCodec;
use App\Lib\Request;
use App\Lib\Response;
use App\Models\RefreshToken;
use App\Models\User;

class AuthController extends Controller {
    public function login() {
        if (Authentication::attempt(Request::body("email"), Request::body("password"))) {
            $token = Authentication::getUser()->createToken();
            return ["content" => ["token" => $token]];
        } else {
            return Response::json(["message" => "Invalid credentials"], 401);
        }
    }

    public function register() {
        $email_exists = User::emailExists(Request::body("email"));
        if ($email_exists) {
            return Response::json(["error" => "Email already exists"], 400);
        }

        $user = new User();
        $user->name = Request::body("name");
        $user->email = Request::body("email");
        $user->password = password_hash(Request::body("password"), PASSWORD_BCRYPT);
        $user->save();
        
        return ["content" => $user];
    }

    public function init() {
        $user = null;

        if ($token = Helpers::getBearerToken()) {
            $user = Authentication::newSessionFromToken($token);
        }

        return [
            "user" => $user,
        ];
    }
}