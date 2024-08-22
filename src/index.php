<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php";


use Framework\Parser;
use Framework\ProjectsParser;
use Framework\Controller\MainController;
use Framework\Models\PartnerModel;
use Framework\Models\ProjectModel;
use Framework\Services\Paginator;
use Framework\Services\ProjectService;

ini_set('max_execution_time', '10000');
set_time_limit(0);
ini_set('memory_limit', '4096M');
ignore_user_abort(true);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$partnerId = isset($_GET['partner_id']) ? (int)$_GET['partner_id'] : 0;

$url = "https://www.1c-bitrix.ru/partners/index_ajax.php";

$projectService = new ProjectService();
$paginator = new Paginator();
$controller = new MainController($projectService, $paginator);

if ($partnerId > 0) {
    $controller->showProjects($partnerId, $page);
} else {
    $controller->showPartners($page);
}
//$parser = new Parser();
// $projectParser = new ProjectsParser();
// $projectParser->parse();
//$html = $parser->parsePartners($url);
//print_r($html);
//$parser->parsAllPartners();
// $DB = new CDatabase();
// $file = fopen('newdata.txt', 'r');

// while (!feof($file)) {
//     $line = fgets($file);
//     if ($line === false) {
//         continue;
//     }
//     $arr = explode(',', $line);

//     $partnerData = [
//         'partner_id' => $arr[0],
//         'project_url' => $arr[1],
//         'product_version' => $arr[2],
//         'description' => $arr[3]
//     ];

//     $PARTNERMODEL = ProjectModel::create($partnerData);
// }

// fclose($file);
// $PARTNERMODEL = new PartnerModel();
//$API = new CApi();
