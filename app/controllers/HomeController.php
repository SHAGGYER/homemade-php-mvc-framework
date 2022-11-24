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
        $users = User::all();
        return ["content" => $users];
    }

    public function getUsersPaginate() {
        $users = User::paginate($_GET["page"] ?? 1, 10)
            ->where([
                    ["id", ">", 0]
                ])
            ->orWhere([
                    ["id", "<", 100]
            ])
            ->get();

        return ["content" => $users];
    }

    public function getUserById($id) {
        $user = User::where([
            ["id", "=", $id]
        ])->first();

        return ["content" => $user];
    }

    
}