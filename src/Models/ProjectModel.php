<?php

namespace Framework\Models;

use Framework\Repository\ProjectRepository;

class ProjectModel extends Model
{
    protected static string $repository = ProjectRepository::class;

    public static function getProjectsById(int $id, int $limit = 20, int $offset = 0): array
    {
        return static::$repository::getProjectsById($id, $limit, $offset);
    }

    public static function getTotalProjectsForPartner(int $id): int
    {
        return static::$repository::getTotalProjectsById($id);
    }
}
