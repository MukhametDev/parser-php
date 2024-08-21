<?php

namespace Framework\Repository;

use Framework\CDatabase;

abstract class Repository
{
    protected static string $table;

    public static function getDatabase(): CDatabase
    {
        return CDatabase::getInstance();
    }

    public function findAll(): ?self
    {
        $table = static::$table;
        $sql = "SELECT * FROM {$table}";
        $items = self::getDatabase()->fetchAll($sql);
        if (!empty($items)) {
            foreach ($items as $item) {
                $item =  new static($item);
            }

            return $items;
        }
        return null;
    }

    public function findById(int $id): ?self
    {
        $table = static::$table;
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $data =  self::getDatabase()->fetchAssoc($sql, ["id" => $id]);
        return new static($data);
    }

    public static function create(array $data): string
    {
        $table = static::$table;
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $data = self::getDatabase()->execute($sql, $data);
        return $data ? 'success' : 'error';
    }

    public static function update($id, array $data): bool
    {
        $table = static::$table;
        $setClause = '';

        foreach ($data as $key => $value) {
            $setClause .= "{$key} =: {$value}";
        }

        $sql = "UPDATE {$table} SET {$setClause} WHERE id = :id";
        $arr = array_merge($data, ["id" => $id]);
        $data = self::getDatabase()->execute($sql, $arr);
        return $data;
    }

    public function delete(int $id): bool
    {
        return static::deleteExec($id);
    }

    public static function deleteIternal(int $id): bool
    {
        return static::deleteExec($id);
    }

    protected function deleteExec(int $id): bool
    {
        $table = static::$table;
        $sql = "DELETE * FROM {$table} WHERE id = :id";
        $data = self::getDatabase()->execute($sql, ["id" => $id]);
        return $data;
    }

    public function search($conditions): array
    {
        $table = static::$table;
        $query = "SELECT * FROM {$table} WHERE ";
        $clauses = [];
        $params = [];

        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $clauses[] = "{$field} LIKE :{$field}";
                $params[$field] = '%' . $value[0] . '%';
            } else {
                $clauses[] = "{$field} = :{$field}";
                $params[$field] = $value;
            }
        }

        $query .= implode(' AND ', $clauses);
        $data = self::getDatabase()->fetchAll($query, $params);
        return $data;
    }
}
