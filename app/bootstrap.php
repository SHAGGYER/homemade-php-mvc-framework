<?php
session_start();
spl_autoload_register(function($class_name) {
    $path = __DIR__ . "/../" . lcfirst(str_replace("\\", "/", $class_name)) . ".php";
    require_once $path;
});