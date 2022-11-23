<?php


namespace App\Lib;

use App\Models\User;

class Authentication {
    private static User $user;

    public static function login() {
        $_SESSION["user"] = [
            "id" => 1,
            "name" => "John Doe",
            "email" => "john@doe.com"
        ];

        self::$user = new User();
        self::$user->id = $_SESSION["user"]["id"];
        self::$user->name = $_SESSION["user"]["name"];
        self::$user->email = $_SESSION["user"]["email"];
    }

    public static function getUser(): User
    {
        return self::$user;
    }
}