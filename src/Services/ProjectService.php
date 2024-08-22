<?php

namespace Framework\Services;

use Framework\Models\ProjectModel;

class ProjectService
{
    public function getProjectsForPartner(int $partnerId, int $limit = 20, int $offset = 0): array
    {
        return ProjectModel::getProjectsById($partnerId, $limit, $offset);
    }

    public function getTotalProjectsForPartner(int $partnerId): int
    {
        return ProjectModel::getTotalProjectsForPartner($partnerId);
    }
}
