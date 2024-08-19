<?php

namespace Framework;

use DiDom\Document;

class ProjectsParser
{
    public string $filename = 'detail_partner.txt';
    public string $baseUrl = 'https://www.1c-bitrix.ru';

    public function parse()
    {
        $file = fopen('partners_data.txt', 'r');
        $items = [];
        $this->clearFile();

        while (!feof($file)) {
            $line = fgets($file);

            $arr = explode(',', $line);

            $id = $arr[0];
            $link = $arr[2];
            $dom = $this->getDocument($link);
            $projects = $this->getProjects($dom);
            foreach ($projects as $project) {
                $link = $this->getUrl($project);
                $newDom = $this->getDocument($link);
                $content = $this->getInfo($newDom);
                $data = $this->combineData($id, $content);

                $this->insertToFile($data);
            }
            $content = $this->getInfo($dom);
            $content = $this->combineData($id, $content);
            $this->insertToFile($content);
        }

    }

    public function getDocument($url)
    {
       while (true) {
           try {
               $dom = new Document($url, true);
               return $dom;
           } catch (\Throwable $e) {
               sleep(30);
           }

       }
    }

    public function getUrl($project): string {
        return $this->baseUrl . $project->getAttribute('href');
    }
    public function getProjects($dom): array {
        return $dom->find(".partner-project-pane__inner");
    }
    public function getInfo($dom): string {
        $link = $dom->find(".detail-page-list__item-record_value")[2]->text();
        $redaction = $dom->find(".detail-page-list__item-record_value a")[0]->text();
        $description = $dom->find(".detail-page-case")[0]->text();
//        $description = trim($description);
//        $description = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $description));
        return "{$link}, {$redaction}, {$description}";
    }
    public function insertToFile(string $content): void {
        file_put_contents($this->filename, $content, FILE_APPEND);
    }

    public function clearFile(): void {
        file_put_contents($this->filename, '');
    }

    public function combineData(int $id, string $content): string {
        return trim("{$id}, {$content}");
    }
}