<?php

namespace App\Lib;

use App\Traits\ConvertsModelToArray;

interface IModel {
    public function all(): IModel;
    public function select(): IModel;
    public function paginate(): IModel;
    public function where(): array;
    public function orderBy(): string;
}

class Model implements \JsonSerializable {
    use ConvertsModelToArray;

    public string $table = "";
    private array $attributes = [];
    private QueryBuilder $queryBuilder;

    public function hasOne(string $model, string $foreignKey, string $localKey) {
        $model = new $model;
        $model->queryBuilder->where([
            [$foreignKey, "=", $this->{$localKey}]
        ])->first();
        return $model;
    }

    public function belongsTo(string $model, string $foreignKey, string $localKey) {
        $model = new $model;
        $model->queryBuilder->where([
            [$localKey, "=", $this->{$foreignKey}]
        ])->first();
        return $model;
    }

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder($this);
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getQuery(): string {
        return $this->query;
    }

    public static function with(array $relations): QueryBuilder {
        $model = new static();
        return $model->queryBuilder->with($relations);
    }

    public static function paginate(int $page = 1, int $limit = 10) {
        $obj = new static();
        return $obj->queryBuilder->paginate($page, $limit);
    }

    public static function where(array $conditions): QueryBuilder {
        $model = new static();
        $model->queryBuilder->where($conditions);
        return $model->queryBuilder;
    }

    public static function findById(int $id): ?Model {
        $model = new static();
        $result = $model->where([
            ["id", "=", $id]
        ])->first();
        return $result;
    }

    public static function all() {
        $obj = new static();
        return $obj->queryBuilder->select()->get();
    }

    public function toJson() {
        return json_encode($this->attributes);
    }

    public function save() {
        $this->queryBuilder->save();
        return $this;
    }

    public function jsonSerialize() {
        return $this->attributes;
    }
    
}