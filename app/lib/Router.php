<?php

namespace App\Lib;

use App\Helpers\Helpers;

class Router {
    private array $routes = [];
    private ?string $current_route = null;
    private ?string $prev_route = null;

    public function add(string $route, string $callback) {
        $this->routes[$route] = [
            "callback" => $callback,
        ];
        $this->current_route = $route;
        return $this;
    }

    public function middleware(...$args) {
        if ($this->current_route) {
            $this->routes[$this->current_route]["middleware"] = $args;
        }
        
        return $this;
    }

    public function run() {
        Helpers::cors();

        $route = $_SERVER['REQUEST_URI'];
        $route = explode("?", $route)[0];
        
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

        foreach ($this->routes as $route => $data) {
            $route_parts = explode("/", $route);
            array_shift($route_parts);
            
            for ($i = 0; $i < count($route_parts); $i++) {
                preg_match("/{(.*)}/", $route_parts[$i], $matches);
                for ($j = 0; $j < count($parts); $j++) {
                    if ($i == $j) {
                         if ($route_parts[$i] == $parts[$j]) {
                            $current_route = $route;
                            $current_callback = $data["callback"];
                            $this->prev_route = $route;
                            continue;
                        } elseif (!empty($matches[1]) && $this->prev_route == $route) {
                            $current_route = $route;
                            $current_callback = $data["callback"];
                            $current_params[] = $parts[$j];
                            continue;
                        } else {
                            $current_route = null;
                            break;
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

        if (isset($this->routes[$current_route]["middleware"])) {
            foreach ($this->routes[$current_route]["middleware"] as $middleware) {
                $middleware = new $middleware;
                $middleware->handle();
            }
        }

        if ($current_callback && $current_route) {
            $class_parts = explode("@", $current_callback);
            $class = $class_parts[0];
            $method = $class_parts[1];
            $class_name = "\\App\\Controllers\\" . $class;
            Container::set($class_name, $class_name);
            $class = Container::get($class_name);
            $response = $class->$method(...$current_params);
            if (!empty($response)) {
                echo Response::json($response);
                exit;
            }
        } else {
            echo "404";
        }
        
    }
}