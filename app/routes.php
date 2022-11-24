<?php

use App\Lib\Container;
use App\Middleware\AuthenticateMiddleware;

$router = Container::get("router");

// Path: app\routes.php
$router->get("/auth/init", "HomeController@init");
$router->post("/auth/login", "HomeController@login");
$router->post("/auth/register", "HomeController@register");
$router->get("/users/{id}", "HomeController@getUserById")->middleware(AuthenticateMiddleware::class);
$router->get("/users", "HomeController@getUsers")->middleware(AuthenticateMiddleware::class);
$router->get("/protected", "HomeController@protected")->middleware(AuthenticateMiddleware::class);
$router->get("/", "HomeController@index");

return $router;