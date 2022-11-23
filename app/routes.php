<?php

use App\Lib\Container;
use App\Middleware\AuthenticateMiddleware;

$router = Container::get("router");

// Path: app\routes.php
$router->add("/auth/init", "HomeController@init");
$router->add("/users/{id}", "HomeController@getUserById");
$router->add("/", "HomeController@index");
$router->add("/test/{name}", "HomeController@index");
$router->add("/test/{name}/test2", "HomeController@index");
$router->add("/login", "HomeController@login");
$router->add("/users", "HomeController@getUsers");
$router->add("/protected", "HomeController@protected")->middleware(AuthenticateMiddleware::class);

return $router;