<?php

namespace App\Controllers;

use App\Helpers\Helpers;
use App\Lib\Authentication;
use App\Lib\Controller;
use App\Lib\Request;
use App\Lib\Response;
use App\Models\User;

class HomeController extends Controller {
    public function index() {
        echo "Hello World!s";
    }

    public function getUsers() {
        $users = User::paginate($_GET["page"] ?? 1, 10)->get();

        return ["content" => $users];
    }

    public function getUserById($id) {
        $user = User::where([
            ["id", "=", $id]
        ])->first();

        return ["content" => $user];
    }

    public function login() {
        if (Authentication::attempt(Request::body("email"), Request::body("password"))) {
            $token = Authentication::getUser()->getToken();
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
        
        $token = Authentication::login($user)->getToken();

        return ["content" => $user, "token" => $token];
    }

    public function init() {
        $user_id = Authentication::getUser()->id;

        $user = User::with(["roles", "token"])->where([
            ["id", "=", $user_id]
        ])->first();

        return [
            "user" => $user,
        ];
    }
}