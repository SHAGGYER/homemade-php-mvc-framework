<?php

use App\Middleware\AuthenticateMiddleware;

require_once "../app/bootstrap.php";
require_once "./router.php";

$router = new Router();

$router->add("/", "HomeController@index");
$router->add("/test/{name}", "HomeController@index");
$router->add("/test/{name}/test2", "HomeController@index");
$router->add("/login", "HomeController@login");
$router->add("/protected", "HomeController@protected")->middleware(AuthenticateMiddleware::class);



$router->run();