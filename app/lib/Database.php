<?php

namespace App\Lib;

class Database {
    private static $args = [
        "host" => "localhost",
        "db" => "",
        "user" => "",
        "pass" => ""
    ];

    public static \PDO $pdo;

    public static function connect($user, $pass, $host, $db): \PDO {
        self::$args["user"] = $user;
        self::$args["pass"] = $pass;
        self::$args["host"] = $host;
        self::$args["db"] = $db;

        try {
            self::$pdo = new \PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
            return self::$pdo;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}