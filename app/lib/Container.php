<?php

namespace App\Lib;

class Container {
    private static array $services = [];

    public static function get(string $key) {
        return self::$services[$key];
    }

    public static function set(string $key, $service) {
        if (is_string($service)) {
            self::$services[$key] = new $service();
        } elseif (is_object($service)) {
            self::$services[$key] = $service;
        }
    }
}