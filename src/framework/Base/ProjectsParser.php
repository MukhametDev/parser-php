<?php

namespace Framework;

use DiDom\Document;
use Exception;

class ProjectsParser
{
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
                    $this->log("Empty line at line number: {$lineNumber}");
                    continue;
                }

                $arr = explode(',', $line);
                if (count($arr) < 3) {
                    $this->log("Invalid line format at line number: {$lineNumber} - Content: {$line}");
                    continue;
                }

                $id = $arr[0];
                $link = $arr[2];
                $this->log("Processing ID: $id with link: $link at line number: {$lineNumber}");

                $dom = $this->getDocument($link);
                if ($dom === null) {
                    $this->log("Skipping ID: $id due to failed document retrieval at line number: {$lineNumber}");
                    continue;
                }

                $projects = $this->getProjects($dom);

                if (empty($projects)) {
                    $this->log("No projects found for ID: $id with link: $link at line number: {$lineNumber}");
                    continue;
                }

                foreach ($projects as $project) {
                    $link = $this->getUrl($project);
                    $newDom = $this->getDocument($link);
                    if ($newDom === null) {
                        $this->log("Skipping project due to failed document retrieval at line number: {$lineNumber}");
                        continue;
                    }
                    $content = $this->getInfo($newDom);
                    $data = $this->combineData($id, $content);
                    $this->insertToFile($data);
                }

                // sleep($this->requestInterval); // Uncomment for production use
            }
            fclose($file);
        } catch (Exception $e) {
            $this->log("Exception occurred: " . $e->getMessage());
        }
    }

    public function getDocument($url)
    {
        while (true) {
            try {
                $this->log("Fetching document from URL: {$url}");

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
                    $this->log("404 Not Found at URL: {$url}");
                    return null; // Пропускаем страницу, если она не найдена
                }

                $dom = new Document($url, true);
                return $dom;
            } catch (\Throwable $e) {
                $this->log("Failed to fetch document at URL: {$url} - Error: " . $e->getMessage());
                sleep(30); // Пропускаем в случае ошибки
            }
        }
    }

    public function getUrl($project): string
    {
        return $this->baseUrl . $project->getAttribute('href');
    }

    public function getProjects($dom): array
    {
        return $dom->find(".partner-project-pane__inner");
    }

    public function getInfo($dom): string
    {
        $link = $dom->find(".detail-page-list__item-record_value")[2]->text();
        $redaction = $dom->find(".detail-page-list__item-record_value a")[0]->text();
        $description = $dom->find(".detail-page-case")[0]->text();
        $description = preg_replace('/\s+/', ' ', $description);

        return "{$link}, {$redaction}, {$description}\n";
    }

    public function insertToFile(string $content): void
    {
        file_put_contents($this->filename, $content, FILE_APPEND);
        $this->log("Data inserted to file: {$this->filename}");
    }

    public function clearFile(): void
    {
        file_put_contents($this->filename, '');
        $this->log("File cleared: {$this->filename}");
    }

    public function combineData(int $id, string $content): string
    {
        return "{$id}, {$content}";
    }

    private function log(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$message}\n";
        file_put_contents($this->logFilename, $logEntry, FILE_APPEND);
    }
}
