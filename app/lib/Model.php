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

class Model extends \stdClass implements \JsonSerializable {
    use ConvertsModelToArray;

    public bool $timestamps = true;
    public string $table = "";
    public array $hidden = [];
    private array $attributes = [];
    public QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder($this);
    }

    public function belongsToMany(string $model, string $pivot_table, string $pivot_one, string $pivot_two): Collection {
        $qb = new QueryBuilder(new static());

        $results = $qb->from($pivot_table)->where([
            [$pivot_one, "=", $this->attributes["id"]]
        ])->get();

        $collection = [];
        foreach ($results->toArray() as $result) {
            $obj = new $model();
            $collection[] = $obj->queryBuilder->where([
                ["id", "=", $result[$pivot_two]]
            ])->first();
        }

        return new Collection($collection);
    }

    public function hasOne(string $model, string $foreignKey, string $localKey = "id"): Model {
        $model = new $model;
        return $model->queryBuilder->where([
            [$foreignKey, "=", $this->{$localKey}]
        ])->first();
    }

    public function belongsTo(string $model, string $foreignKey, string $localKey) {
        $model = new $model;
        return $model->queryBuilder->where([
            [$localKey, "=", $this->{$foreignKey}]
        ])->first();
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

    public static function delete() {
        $obj = new static();
        return $obj->queryBuilder->delete();
    }

    public function toJson() {
        return json_encode($this->attributes);
    }

    public function save() {
        $this->queryBuilder->save();
        return $this;
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

    public function jsonSerialize() {
        return array_filter($this->attributes, function($value, $key) {
            return !in_array($key, $this->hidden);
        }, ARRAY_FILTER_USE_BOTH);
    }
}