<?php

namespace App\Lib;

use App\Helpers\Helpers;

class Router {
    private array $routes = [];
    private array $middleware = [];
    private array $current_route = [];

    public function add(string $route, string $callback) {
        $this->routes[$route] = $callback;
        return $this;
    }

    public function middleware(string $class) {
        if ($this->current_route) {
            $this->middleware[] = $class;
        }
        
        return $this;
    }

    public function run() {
        Helpers::cors();

        $route = $_SERVER['REQUEST_URI'];
        $last_char = $route[-1];
        if ($last_char === "/") {
            $route = substr($route, 0, -1);
        }

        $parts = explode("/", $route);
        array_shift($parts);

        // current route is /
        if (!count($parts)) {
            $parts[] = "";
        }

        $current_route = null;
        $current_callback = null;
        $current_params = [];

        foreach ($this->routes as $route => $callback) {
            $route_parts = explode("/", $route);
            array_shift($route_parts);
            
            for ($i = 0; $i < count($route_parts); $i++) {
                preg_match("/{(.*)}/", $route_parts[$i], $matches);

                for ($j = 0; $j < count($parts); $j++) {

                    if ($i == $j) {
                        if (!empty($matches[1])) {
                            $current_route = $route;
                            $current_callback = $callback;
                            $current_params[] = $parts[$j];
                            continue;
                        } elseif ($route_parts[$i] == $parts[$j]) {
                            $current_route = $route;
                            $current_callback = $callback;
                            continue;
                        } else {
                            $current_route = null;
                            continue;
                        }
                    } else {
                        $current_route = null;
                        continue;
                    }
                                
                }
            }

            if ($current_route) {
                break;
            }
        }

        foreach ($this->middleware as $middleware) {
            $middleware = new $middleware;
            $middleware->handle();
        }

        if ($current_callback && $current_route) {
            $class_parts = explode("@", $current_callback);
            $class = $class_parts[0];
            $method = $class_parts[1];
            $class_name = "\\App\\Controllers\\" . $class;
            Container::set($class_name, $class_name);
            if (count($current_params) > 0) {
                $class = Container::get($class_name);
                $class->$method(...$current_params);
            } else {
                $class = Container::get($class_name);
                $class->$method();
            }
        } else {
            echo "404";
        }
        
    }
}