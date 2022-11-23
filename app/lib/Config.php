<?php

namespace App\Lib;

class Config {
    private array $config = [];

    public function get(string $key) {
        return $this->config[$key];
    }

    public function load($file) {
        $configFile = file_get_contents($file);
        $this->config = json_decode($configFile, true);
    }
}