<?php

namespace Framework\Repository;

class ProjectRepository extends Repository
{
    protected static string $table = "projects";

    public static function getProjectsById(int $id, int $limit = 20, int $offset = 0): array
    {
        $table = static::$table;
        $sql = "SELECT * FROM {$table} WHERE partner_id = $id LIMIT $limit OFFSET $offset";
        $data = self::getDatabase()->fetchAll($sql);
        return $data;
    }

    public static function getTotalProjectsById(int $id): int
    {
        $table = static::$table;
        $sql = "SELECT COUNT(*) FROM {$table} WHERE partner_id = $id";
        $data = self::getDatabase()->fetchColumn($sql);
        return $data;
    }
}
