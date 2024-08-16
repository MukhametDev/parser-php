<?php

namespace Framework;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class Parser
{
    public $baseUrl = 'https://www.1c-bitrix.ru/partners/';
    public $filePath = 'partners_data.txt';
    public $logPath = 'logs.log';


    public function parsAllPartners()
    {
        $endpoint = 'index_ajax.php?PAGEN_1=';
        $pagenValue = 1;

        while ($pagenValue <= 120) {
            try {
                $baseDocument = new Document($this->baseUrl . $endpoint . $pagenValue, true);

                $this->parsPartnerBlock($baseDocument);
                $this->writeToFile($this->logPath, 'PAGEN=' . $pagenValue . "\n");
            } catch (\Throwable $e) {
                $this->writeToFile($this->logPath, $e->getMessage() . "\n");
                sleep(600);
                continue;
            }
            $pagenValue++;
        }
    }

    public function parsPartnerBlock($baseDocument)
    {
        $endpoints = $baseDocument->find('.bx-ui-tile__main-link');

        foreach ($endpoints as $endpoint) {
            try {
                $detailPage = $this->getDetailPage($endpoint);

                $document = new Document($detailPage, true);

                $name = $this->getPartnerName($document);
                $partnerAddress = $this->getPartnerAddress($document);

                $content = "{$this->getNewId()}, {$name}, {$detailPage}, {$partnerAddress}\n";
                echo $content;
                $this->writeToFile($this->filePath, $content);
            } catch (\Throwable $e) {
                $this->writeToFile($this->logPath, 'Error in parsPartnerBlock: ' . $e->getMessage());
                sleep(60);
                continue;
            }
        }
    }

    public function getDetailPage($partner)
    {
        return $this->baseUrl . ltrim($partner->getAttribute('href'), './');
    }

    public function getPartnerName($document)
    {
        try {
            return $document->first('.partner-card-profile-header-title')->text();
        } catch (\Throwable $e) {
            $this->writeToFile($this->logPath, 'Error in getPartnerName: ' . $e->getMessage() . "\n");
            return 'Unknown Name';
        }
    }

    public function getPartnerAddress($document)
    {
        try {
            return $document->first('.simple-link')->getAttribute('href');
        } catch (\Throwable $e) {
            $this->writeToFile($this->logPath, 'Error in getPartnerAddress: ' . $e->getMessage() . "\n");
            return 'Unknown Address';
        }
    }

    public function writeToFile($filePath, $fileContent)
    {
        file_put_contents($filePath, $fileContent, FILE_APPEND);
    }

    public function getNewId()
    {
        static $id = 1;
        return $id++;
    }
}
//class Parser
//{
//    private $client;
//    private $db;
//
//    public $filename = "links.txt";
//    public $baseUrl = "https://www.1c-bitrix.ru/partners/";
//    public $errorLogFile = "error_log.txt";
//    public $id = 0;
//
//    public function __construct()
//    {
//        $this->client = new Client();
//        $this->db = CDatabase::getInstance();
//    }
//
//    public function parsePartners(string $url)
//    {
//        $count  = 120;
//        $pageNumber = 1;
//
//        file_put_contents($this->filename, '');
//        while ($count > 0) {
//            $fullUrl = $url . "?PAGEN_1=" . $pageNumber;
//            try {
//                $dom = new Document($fullUrl, true);
//                $partners = $dom->find('.bx-ui-tile__main-link');
//
//                foreach ($partners as $partner) {
//                    try {
//                        $transformedUrl = $this->transformUrl($partner);
//                        $newDom = new Document($transformedUrl, true);
//                        $content = $this->getInfo($newDom, $transformedUrl);
//
//                        // Запись в файл только если все данные валидны
//                        if ($this->isValidData($content)) {
//                            $this->insertInFile($content);
//                        }
//                    } catch (RequestException $e) {
//                        $this->logError($e->getMessage());
//                    } catch (InvalidSelectorException $e) {
//                        $this->logError($e->getMessage());
//                    }
//                }
//            } catch (RequestException $e) {
//                $this->logError($e->getMessage());
//            } catch (InvalidSelectorException $e) {
//                $this->logError($e->getMessage());
//            }
//
//            $count--;
//            $pageNumber++;
//        }
//    }
//
//    protected function transformUrl($partner)
//    {
//        return $this->baseUrl . ltrim($partner->getAttribute('href'), './');
//    }
//
//    protected function generateId()
//    {
//        return $this->id++;
//    }
//
//    protected function insertInFile($content)
//    {
////        file_put_contents($this->filename, $content, FILE_APPEND);
//        // Открываем файл в режиме добавления
//        $file = fopen($this->filename, 'a');
//
//        // Пишем содержимое в файл
//        fwrite($file, $content);
//
//        // Принудительно сбрасываем буфер, чтобы записать данные на диск
//        fflush($file);
//
//        // Закрываем файл
//        fclose($file);
//    }
//
//    protected function getInfo($dom, $url = null)
//    {
//        try {
//            $title = $dom->first('.partner-card-profile-header-title')->text();
//            $link = $dom->first('.simple-link')->getAttribute('href');
//
//            // Возвращаем строку с переносом строки
//            return $this->generateId() . ", " . $title . ", " . $url . ", " . $link . PHP_EOL;
//        } catch (InvalidSelectorException $e) {
//            $this->logError($e->getMessage());
//            return null;
//        }
//    }
//
//    protected function isValidData($data)
//    {
//        if (is_null($data)) {
//            return false;
//        }
//
//        $parts = array_map('trim', explode(',', $data));
//
//        if (count($parts) !== 4) {
//            return false;
//        }
//
//        list($id, $title, $url, $link) = $parts;
//
//        return !empty($title) && !empty($link) && !empty($url);
//    }
//
//    protected function logError($message)
//    {
//        file_put_contents($this->errorLogFile, $message . PHP_EOL, FILE_APPEND);
//    }
//}

