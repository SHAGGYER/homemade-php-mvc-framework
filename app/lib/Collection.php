<?php

namespace App\Lib;

class Collection implements \JsonSerializable {
    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function first()
    {
        return $this->items[0];
    }

    public function last()
    {
        return $this->items[count($this->items) - 1];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    public function filter(callable $callback): array
    {
        return array_filter($this->items, $callback);
    }

    public function each(callable $callback): void
    {
        foreach ($this->items as $item) {
            $callback($item);
        }
    }

    public function toArray(): array
    {
        return $this->map(function($item) {
            return $item->toArray();
        });
    }

    public function jsonSerialize()
    {
        return $this->items;
    }
}