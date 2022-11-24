<?php

namespace App\Lib;

use App\Traits\ConvertsModelToArray;

class QueryBuilder {
    use ConvertsModelToArray;

    private ?string $table;
    private bool $has_select = false;
    private \PDO $pdo;
    private array $values = [];
    private array $attributes = [];
    private Model $model;

    public function __construct(Model $model = null)
    {
        $this->query = "";
        $this->table = $model->table;
        $this->pdo = Database::$pdo;
        $this->model = $model;
    }

    public function save() {
        $this->attributes = $this->model->getAttributes();
        $this->appendTimestamps();
        if (isset($this->attributes["id"])) {
            $this->update()->where([
                ["id", "=", $this->attributes["id"]]
            ])->execute();
        } else {
            $this->insert()->execute();
        }
    }

    public function insert(): QueryBuilder {
        $this->query = "INSERT INTO {$this->table} (";
        $this->query .= implode(", ", array_keys($this->attributes));
        $this->query .= ") VALUES (";
        $this->query .= implode(", ", array_fill(0, count($this->attributes), "?"));
        $this->query .= ")";
        $this->values = array_values($this->attributes);

        return $this;
    }

    public function update(): QueryBuilder {
        $this->attributes = $this->model->getAttributes();

        $this->query = "UPDATE {$this->table} SET ";
        $this->query .= implode(", ", array_map(function($key) {
            return "{$key} = ?";
        }, array_keys($this->attributes)));
        $this->values = array_values($this->attributes);
        return $this;
    }

    public function execute(): bool {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->values);
        return true;
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

    public function first(): ?Model {
        $this->query .= " LIMIT 1";

        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->values);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            foreach ($row as $column => $value) {
                $this->model->{$column} = $value;
            }
            return $this->model;
        }

        return null;
    }

    public function get(): Collection {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $models = [];

        foreach ($rows as $row) {
            $model = new $this->model;
            foreach ($row as $column => $value) {
                $model->{$column} = $value;
            }
            $models[] = $model;
        }

        return new Collection($models);
    }

    public function orderBy($column, $order) {
        $this->query .= " ORDER BY " . $column . " " . $order;
        return $this;
    }

    private function appendTimestamps() {
        $this->attributes["created_at"] = date("Y-m-d H:i:s");
        $this->attributes["updated_at"] = date("Y-m-d H:i:s");
    }
}