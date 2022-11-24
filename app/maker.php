<?php

use App\Commands\MakeController;
use App\Commands\MakeMigration;
use App\Commands\MakeModel;
use App\Lib\Migration;

require_once __DIR__ . "/bootstrap.php";
define("APP_PATH", __DIR__);

$data = [];
parse_str(implode('&', array_slice($argv, 1)), $data);

foreach ($data as $command => $value) {
    switch($command) {
        case "make:controller":
            $maker = new MakeController();
            $maker->handle($value);
            echo "Controller created successfully";
            break;
        case "make:migration":
            $maker = new MakeMigration();
            $maker->handle($value);
            echo "Migration created successfully";
            break;
        case "make:model":
            $maker = new MakeModel();
            $maker->handle($value);
            echo "Model created successfully";
            break;
        case "migrate":
            Migration::loadUp();
            echo "Migrations loaded successfully";
            break;
        case "migrate:down":
            Migration::loadDown();
            echo "Migrations rolled back successfully";
            break;
    }
}