<?php

namespace App\Lib;

class Container {
    private static array $services = [];

    public static function get(string $key) {
        return self::resolve(self::$services[$key]);
    }

    public static function set(string $key, $service) {
        self::$services[$key] = $service;
    }

    private static function resolve($id): object {
        try {
            $reflectionClass = new \ReflectionClass($id);
        } catch(\ReflectionException $e) {
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

        if (! $reflectionClass->isInstantiable()) {
            throw new \Exception('Class "' . $id . '" is not instantiable');
        }

        $constructor = $reflectionClass->getConstructor();
        if (! $constructor) {
            return new $id;
        }

        $parameters = $constructor->getParameters();
        if (! $parameters) {
            return new $id;
        }

        $dependencies = array_map(
            function (\ReflectionParameter $param) use ($id) {
                $name = $param->getName();
                $type = $param->getType();

                if (! $type) {
                    throw new \Exception(
                        'Failed to resolve class "' . $id . '" because param "' . $name . '" is missing a type hint'
                    );
                }

                if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                    return self::get($type->getName());
                }

                throw new \Exception(
                    'Failed to resolve class "' . $id . '" because invalid param "' . $name . '"'
                );
            },
            $parameters
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}