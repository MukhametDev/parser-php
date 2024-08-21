<?php

namespace Framework\View;

class Paginator
{
    private int $currentPage;
    private int $perPage = 20;
    private int $total;

    public function getPageCount(): int
    {
        return ceil($this->total / $this->perPage);
    }

}
