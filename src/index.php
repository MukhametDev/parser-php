<?php

use Framework\Parser;
use Framework\ProjectsParser;

ini_set('max_execution_time', '10000');
set_time_limit(0);
ini_set('memory_limit', '4096M');
ignore_user_abort(true);
include_once "./framework/framework.php";

$url = "https://www.1c-bitrix.ru/partners/index_ajax.php";

//$parser = new Parser();
$projectParser = new ProjectsParser();
$projectParser->parse();
//$html = $parser->parsePartners($url);
//print_r($html);
//$parser->parsAllPartners();
