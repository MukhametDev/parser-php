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
// $file = fopen('newdata.txt', 'r');

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
$file = fopen('projects.txt', 'r');
$filewriter = fopen('newdata.txt', 'w');

$buffer = '';
$inRow = false;

while (!feof($file)) {
    $line = fgets($file);
    if ($line === false) {
        continue;
    }

    // Начинаем буферизацию при нахождении начального тега <row>
    if (strpos($line, '<row>') !== false) {
        $inRow = true;
        $buffer = $line;
    } elseif ($inRow) {
        // Добавляем строки в буфер, пока не найдем конечный тег </row>
        $buffer .= $line;
        if (strpos($line, '</row>') !== false) {
            $inRow = false;

            // Удаляем начальный и конечный тег <row>
            $buffer = trim($buffer, "<row>");

            // Разделяем строку по разделителю <#>
            $arr = explode('<#>', $buffer);

            // Проверяем, что массив содержит как минимум 4 элемента
            if (count($arr) >= 4) {
                $id = trim($arr[0]);
                $site = trim($arr[1]);
                $category = trim($arr[2]);
                $description = trim($arr[3]);

                // Добавляем http к названию сайта
                if (!preg_match('/^http(s)?:\/\//', $site)) {
                    $site = 'http://' . $site;
                }
                $description = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
                $description = preg_replace('/&nbsp;/', ' ', $description);
                $description = preg_replace('/[\x00-\x1F\x7F]/', '', $description); // Удаляем непечатные символы
                // Записываем данные в новый файл
                $newLine = "{$id}, {$site}, {$category}, {$description}\n";
                // dd($newLine);
                fwrite($filewriter, $newLine);
            }

            // Очищаем буфер
            $buffer = '';
        }
    }
}

fclose($file);
fclose($filewriter);
// $PARTNERMODEL = new PartnerModel();
//$API = new CApi();
