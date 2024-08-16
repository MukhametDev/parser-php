<?php

namespace Framework\Models;

use Framework\CDatabase;

abstract class Model
{
    protected array $data;
    protected static string $repository;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function findAll(): array
    {
        return static::$repository::findAll();
    }

    public static function findById(int $id): self
    {
        return static::$repository::findById($id);
    }

    public static function create(array $data): string
    {
        return static::$repository::create($data);
    }

    public static function update(int $id, array $data): bool
    {
        return static::$repository::update($id, $data);
    }

    public function delete(int $id): bool
    {
        return static::$repository::deleteExec($id);
    }

    public static function deleteIternal(int $id): bool
    {
        return static::$repository::deleteExec($id);
    }

    protected static function deleteExec(int $id): bool
    {
        return static::$repository::deleteExec($id);
    }

    public function __call(string $name, array $args)
    {
        if (strpos($name, 'get') !== 0) {
            return null;
        }

        $fieldName = strtolower(substr($name, 3));
        return $this->data[$fieldName] ?? null;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function search(array $conditions): array
    {
        return static::$repository::search($conditions);
    }
}
