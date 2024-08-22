<?php

namespace Framework\Controller;

use Framework\Services\ProjectService;
use Framework\Models\PartnerModel;
use Framework\Services\Paginator;
use Framework\Models\ProjectModel;

class MainController
{
    protected ProjectService $projectService;
    protected Paginator $paginator;

    public function __construct(ProjectService $projectService, Paginator $paginator)
    {
        $this->projectService = $projectService;
        $this->paginator = $paginator;
    }

    public function showPartners(int $page): void
    {
        $this->paginator->setCurrentPage($page);
        $partners = $this->paginator->getData();
        $totalPages = $this->paginator->getPageCount(PartnerModel::getTotalCount());

        include "./View/Page.php";
    }

    public function showProjects(int $partnerId, int $page): void
    {
        $this->paginator->setCurrentPage($page);
        $limit = $this->paginator->getPerPage();
        $offset = ($page - 1) * $limit;
        $projects = ProjectModel::getProjectsById($partnerId, $limit, $offset);
        $totalProjects = ProjectModel::getTotalProjectsForPartner($partnerId);
        $totalPages = $this->paginator->getPageCount($totalProjects);

        include "./View/Projects.php";
    }
}
