<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php";

use Framework\Parser;
use Framework\ProjectsParser;
use Framework\CMain;
use Framework\CDatabase;
use Framework\CApi;
use Framework\CUser;
use Framework\Models\PartnerModel;
use Framework\Models\ProjectModel;
use Framework\Validators\Validator;
use Framework\Validators\UserValidator;
use Symfony\Component\VarDumper\VarDumper;

ini_set('max_execution_time', '10000');
set_time_limit(0);
ini_set('memory_limit', '4096M');
ignore_user_abort(true);
include_once "./framework/framework.php";

$url = "https://www.1c-bitrix.ru/partners/index_ajax.php";

//$parser = new Parser();
// $projectParser = new ProjectsParser();
// $projectParser->parse();
//$html = $parser->parsePartners($url);
//print_r($html);
//$parser->parsAllPartners();
$DB = new CDatabase();
// $file = fopen('recap_data.txt', 'r');

// while (!feof($file)) {
//     $line = fgets($file);
//     if ($line === false) {
//         continue;
//     }
//     $arr = explode(',', $line);
//     // array_shift($arr);
//     $partnerData = [
//         'partner_id' => $arr[0],
//         'project_url' => $arr[1],
//         'product_version' => $arr[2],
//         'description' => $arr[3]
//     ];

//     $PARTNERMODEL = ProjectModel::create($partnerData);

//     // $PARTNERMODEL->create($arr);
// }

// fclose($file);

$file = fopen('partners.txt', 'r');
$filewriter = fopen('newdata.txt', 'w');

while (!feof($file)) {
    $line = fgets($file);
    if ($line === false) {
        continue;
    }

    $arr = explode(':', $line);
    dd($arr);
}

// $PARTNERMODEL = new PartnerModel();
//$API = new CApi();
