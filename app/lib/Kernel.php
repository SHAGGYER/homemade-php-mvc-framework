<?php

namespace App\Lib;

class Kernel {
    public Database $db;

    private Router $router;

    public function __construct()
    {

    }

    public function run() {
        Database::connect("root", "", "localhost", "suborgia");
        
        Container::set("router", \App\Lib\Router::class);
        $this->loadRoutes();
    }

    private function loadRoutes() {
        $router = require_once __DIR__ . "/../routes.php";
        $this->router = $router;
        $this->router->run();
    }
}