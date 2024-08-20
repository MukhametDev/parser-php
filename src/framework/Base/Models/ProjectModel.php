<?php

namespace Framework\Models;

use Framework\Repository\ProjectRepository;

class ProjectModel extends Model
{
    protected static string $repository = ProjectRepository::class;
}