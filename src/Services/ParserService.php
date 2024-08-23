<?php

namespace Framework\Services;

use Framework\Services\Parser;
use Framework\Services\ProjectsParser;

class ParserService
{
    protected Parser $parser;
    protected ProjectsParser $projectsParser;

    public function __construct(Parser $parser, ProjectsParser $projectsParser)
    {
        $this->parser = $parser;
        $this->projectsParser = $projectsParser;
    }

    public function parseAllPartners($url): void
    {
        $this->parser->parsePartners($url);
    }

    public function parseProjects(): void
    {
        $this->projectsParser->parse();
    }
}
