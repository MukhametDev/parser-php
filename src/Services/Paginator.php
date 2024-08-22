<?php

namespace Framework\Services;

use Framework\Models\PartnerModel;
use Framework\Models\ProjectModel;

class Paginator
{
    private int $page = 1;
    private int $perPage = 20;

    public function getPageCount(int $total): int
    {
        return ceil($total / $this->perPage);
    }

    public function getCurrentPage(): int
    {
        return $this->page;
    }

    public function setCurrentPage(int $currentPage): void
    {
        $this->page = $currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getData(): array
    {
        return PartnerModel::getData($this->perPage, ($this->page - 1) * $this->perPage);
    }

    public function getProjectsForPartner(int $partnerId): array
    {
        return ProjectModel::getProjectsById($partnerId, $this->perPage, ($this->page - 1) * $this->perPage);
    }
}
