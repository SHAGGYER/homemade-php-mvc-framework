<?php

namespace App\Controllers;

use App\Lib\Authentication;
use App\Lib\Controller;
use App\Models\User;

class HomeController extends Controller {
    public function index() {
        echo "Hello World!s";
    }

    public function getUsers() {
        $users = User::query()->paginate($_GET["page"] ?? 1, 10)->get();
        echo json_encode([
            "users" => $this->modelsToArray($users),
        ]);
    }

    public function getUserById($id) {
        $user = User::query()->where([
            ["id", "=", $id]
        ])->first();
        echo $user->toJson();
    }

    public function login() {
        Authentication::login();
        $user = Authentication::getUser();
        echo "Hello {$user->name}!";
    }

    public function protected() {
        $user = Authentication::getUser();
        echo "Hello {$user->name}!";
    }
}