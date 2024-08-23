<?php

namespace Framework\Services;

use DiDom\Document;
use Exception;
use Framework\Traits\FileHandlerTrait;
use Framework\Traits\IDGeneratorTrait;
use Framework\Traits\LoggerTrait;

class ProjectsParser
{
    use FileHandlerTrait;
    use LoggerTrait;
    use IDGeneratorTrait;

    public string $filename = 'detail_partner.txt';
    public string $baseUrl = 'https://www.1c-bitrix.ru';
    private int $requestInterval = 10;
    private string $logFilename = 'error_log.txt';

    public function parse()
    {
        try {
            $file = fopen('partners_data.txt', 'r');
            if (!$file) {
                throw new Exception("Failed to open partners_data.txt");
            }
            $this->clearFile();

            $lineNumber = 0;

            while (!feof($file)) {
                $line = fgets($file);
                $lineNumber++;

                if (empty(trim($line))) {
                    $this->logError("Empty line at line number: {$lineNumber}");
                    continue;
                }

                $arr = explode(',', $line);
                if (count($arr) < 3) {
                    $this->logError("Invalid line format at line number: {$lineNumber} - Content: {$line}");
                    continue;
                }

                $id = $arr[0];
                $link = trim($arr[2]);

                if ((int)$id < 653 || (int)$id > 1074) {
                    continue;
                }

                $this->logError("Processing ID: $id with link: $link at line number: {$lineNumber}");

                $dom = $this->getDocument($link);
                if ($dom === null) {
                    $this->logError("Skipping ID: $id due to failed document retrieval at line number: {$lineNumber}");
                    continue;
                }

                $projects = $this->getProjects($dom);

                if (empty($projects)) {
                    $this->logError("No projects found for ID: $id with link: $link at line number: {$lineNumber}");
                    continue;
                }

                foreach ($projects as $project) {
                    $url = $this->getUrl($project);
                    if (empty($url)) {
                        $this->logError("Skipping project due to invalid URL at line number: {$lineNumber}");
                        continue;
                    }

                    $newDom = $this->getDocument($url);
                    if ($newDom === null) {
                        $this->logError("Skipping project due to failed document retrieval at line number: {$lineNumber}");
                        continue;
                    }

                    $content = $this->getInfo($newDom);
                    $data = $this->combineData($id, $content);
                    $this->insertToFile($data);
                }
            }
            fclose($file);
        } catch (Exception $e) {
            $this->logError("Exception occurred: " . $e->getMessage());
        }
    }

    public function getDocument($url)
    {
        while (true) {
            try {
                $this->logError("Fetching document from URL: {$url}");

                // Use curl to get the HTTP status code
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_exec($ch);

                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 404) {
                    $this->logError("404 Not Found at URL: {$url}");
                    return null; // Пропускаем страницу, если она не найдена
                }

                $dom = new Document($url, true);
                return $dom;
            } catch (\Throwable $e) {
                $this->logError("Failed to fetch document at URL: {$url} - Error: " . $e->getMessage());
                sleep(30); // Пропускаем в случае ошибки
            }
        }
    }

    public function getUrl($project): string
    {
        $href = $project->getAttribute('href');
        if (empty($href)) {
            $this->logError("Project href is empty or not found.");
            return "";
        }

        $url = $this->baseUrl . $href;

        $pattern = '/^https:\/\/www\.1c-bitrix\.ru\/products\/cms\/projects\/\d+\/$/';

        if (!preg_match($pattern, $url)) {
            $this->logError("Invalid URL: {$url}");
            return ""; // Возвращаем пустую строку, если URL не соответствует шаблону
        }

        return $url;
    }

    public function getProjects($dom): array
    {
        return $dom->find(".partner-project-pane__inner");
    }

    public function getInfo($dom): string
    {
        $elements = $dom->find(".detail-page-list__item-record_value");

        $link = isset($elements[3]) ? $elements[3]->first('a')->getAttribute('href') : 'N/A';
        $redaction = isset($elements[2]) ? $elements[2]->text() : 'N/A';
        $descriptionElements = $dom->find(".detail-page-case");
        $description = isset($descriptionElements[0]) ? $descriptionElements[0]->text() : 'N/A';
        $description = preg_replace('/\s+/', ' ', $description);

        return "{$link}, {$redaction}, {$description}\n";
    }

    public function combineData(int $id, string $content): string
    {
        return "{$id}, {$content}";
    }
}
