<?php

use App\Lib\Container;
use App\Middleware\AuthenticateMiddleware;

$router = Container::get("router");

// Path: app\routes.php
$router->get("/api/auth/init", "HomeController@init");
$router->post("/api/auth/login", "HomeController@login");
$router->post("/api/auth/register", "HomeController@register");
$router->get("/api/users", "HomeController@getUsers");
$router->get("/api/users/paginate", "HomeController@getUsersPaginate")->middleware(AuthenticateMiddleware::class);
$router->get("/api/users/{id}", "HomeController@getUserById")->middleware(AuthenticateMiddleware::class);
$router->get("/", "HomeController@index");

return $router;