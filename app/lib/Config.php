<?php

namespace App\Lib;

class Config {
    public static function get(string $key) {
        return $_ENV[$key];
    }
}