<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php";


use Framework\Services\Parser;
use Framework\Controller\MainController;
use Framework\Services\Paginator;
use Framework\Services\ParserService;
use Framework\Services\ProjectService;
use Framework\Services\ProjectsParser;

ini_set('max_execution_time', '10000');
set_time_limit(0);
ini_set('memory_limit', '4096M');
ignore_user_abort(true);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$partnerId = isset($_GET['partner_id']) ? (int)$_GET['partner_id'] : 0;

$parser = new Parser();
$projectParser = new ProjectsParser();
$parserService = new ParserService($parser, $projectParser);

$projectService = new ProjectService();
$paginator = new Paginator();
$controller = new MainController($projectService, $paginator, $parserService);

if ($partnerId > 0) {
    $controller->showProjects($partnerId, $page);
} elseif (isset($_GET['action']) && $_GET['action'] === 'parsePartners') {
    $controller->parsePartners();
} elseif (isset($_GET['action']) && $_GET['action'] === 'parseProjects') {
    $controller->parseProjects();
} else {
    $controller->showPartners($page);
}
