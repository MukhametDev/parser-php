<?php

namespace Framework\Controller;

use Framework\Services\ProjectService;
use Framework\Models\PartnerModel;
use Framework\Services\Paginator;
use Framework\Models\ProjectModel;
use Framework\Services\ParserService;

class MainController
{
    protected ProjectService $projectService;
    protected Paginator $paginator;
    protected ParserService $parserService;

    public function __construct(ProjectService $projectService, Paginator $paginator, ParserService $parserService)
    {
        $this->projectService = $projectService;
        $this->paginator = $paginator;
        $this->parserService = $parserService;
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

    public function parsePartners(): void
    {
        $url = "https://www.1c-bitrix.ru/partners/index_ajax.php";
        $this->parserService->parseAllPartners($url);
        echo "Парсинг партнеров завершен!";
    }

    public function parseProjects(): void
    {
        $this->parserService->parseProjects();
        echo "Парсинг проектов завершен!";
    }
}
