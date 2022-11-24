<?php

namespace App\Lib;

class Database {
    public static \PDO $pdo;

    public static function connect(): \PDO {
        $user = Config::get("DB_USER");
        $pass = Config::get("DB_PASS");
        $host = Config::get("DB_HOST");
        $db = Config::get("DB_NAME");

        try {
            self::$pdo = new \PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
            return self::$pdo;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}