<?php

namespace App\Controllers;

use App\Lib\Authentication;

class HomeController {
    public function index() {
        echo "Hello World!";
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