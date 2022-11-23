<?php

namespace App\Lib;

interface IModel {
    public function all(): IModel;
    public function select(): IModel;
    public function paginate(): IModel;
    public function where(): array;
    public function orderBy(): string;
}

class Model extends \stdClass {
    protected string $table = "";
    private string $query;
    private bool $has_select = false;
    private array $attributes = [];
    private \PDO $pdo;
    private array $values = [];

    public function __construct()
    {
        $this->query = "";
        $this->pdo = Database::$pdo;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getQuery(): string {
        return $this->query;
    }

    public static function all() {
        $obj = new static();
        $results = $obj->select()->get();

        return $results;
    }

    public static function query(): Model {
        $obj = new static();
        return $obj;
    }

    public function select(string $columns = "*") {
        $this->query = "SELECT $columns" . " FROM " . $this->table;
        $this->has_select = true;
        return $this;
    }

    public function paginate(int $page, int $limit) {
        if (!$this->has_select) {
            $this->select();
        }

        $offset = ($page - 1) * $limit;
        $this->query .= " LIMIT $limit OFFSET $offset";
        return $this;
    }

    public function where($data) {
        $has_where = false;

        
        foreach ($data as $where) {
            $column = $where[0];
            $operator = $where[1];
            $value = $where[2];

            $this->values[] = $value;

            $sql = "";

            if (!$this->has_select) {
                $sql .= "SELECT * FROM " . $this->table;
                $this->has_select = true;
            }


            if (!$has_where) {
                $sql .= " WHERE " . $column . " " . $operator . " ?";
                $has_where = true;
            } else {
                $sql .= " AND " . $column . " " . $operator . " ?";
            }

        }

        $this->query .= $sql;

        return $this;
    }

    public function first() {
        $this->query .= " LIMIT 1";

        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->values);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $this->attributes = $row;
        }

        return $this;
    }

    public function get() {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $models = [];

        foreach ($rows as $row) {
            $model = new Model();
            $model->attributes = $row;
            $models[] = $model;
        }

        return $models;
    }

    public function orderBy($column, $order) {
        $this->query .= " ORDER BY " . $column . " " . $order;
        return $this;
    }

    public function toJson() {
        return json_encode($this->attributes);
    }
}