<?php

namespace App\Lib;

class Migration
{
    private static array $migrations = [];

    public function up()
    {
    }

    public function down()
    {
    }

    private static function init(): \PDO
    {
        $migrations = glob(__DIR__ . "/../migrations/*.php");
        self::$migrations = array_map(function ($migration) {
            return str_replace(".php", "", basename($migration));
        }, $migrations);

        Config::load();
        $pdo = Database::connect();
        return $pdo;
    }

    public static function loadUp()
    {
        $pdo = self::init();

        foreach (self::$migrations as $migration) {
            $migration = "App\\Migrations\\" . $migration;
            $migration = new $migration;
            var_dump($migration);

            $pdo->prepare($migration->up())->execute();
        }
    }

    public static function loadDown() {
        $pdo = self::init();

        foreach (self::$migrations as $migration) {
            $migration = "App\\Migrations\\" . $migration;
            $migration = new $migration;
            var_dump($migration);

            $pdo->prepare($migration->down())->execute();
        }
    }
}
