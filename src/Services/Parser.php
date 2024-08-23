<?php

namespace Framework\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Framework\Traits\FileHandlerTrait;
use Framework\Traits\IDGeneratorTrait;
use Framework\Traits\LoggerTrait;

class Parser
{
    use FileHandlerTrait;
    use LoggerTrait;
    use IDGeneratorTrait;

    public $filename = "links.txt";
    public $baseUrl = "https://www.1c-bitrix.ru/partners/";
    public $errorLogFile = "error_log.txt";
    public $id = 0;

    public function parsePartners(string $url)
    {
        $count  = 120;
        $pageNumber = 1;

        $this->clearFile();

        while ($count > 0) {
            $fullUrl = $url . "?PAGEN_1=" . $pageNumber;
            try {
                $dom = $this->getDocument($fullUrl);
                $partners = $dom->find('.bx-ui-tile__main-link');

                foreach ($partners as $partner) {
                    try {
                        $transformedUrl = $this->transformUrl($partner);
                        $newDom = new Document($transformedUrl, true);
                        $content = $this->getInfo($newDom, $transformedUrl);

                        // Запись в файл только если все данные валидны
                        if ($this->isValidData($content)) {
                            $this->insertToFile($content);
                        }
                    } catch (RequestException $e) {
                        $this->logError($e->getMessage());
                    } catch (InvalidSelectorException $e) {
                        $this->logError($e->getMessage());
                    } catch (\Throwable $e) {
                        $this->logError($e->getMessage());
                        sleep(30);
                    }
                }
            } catch (RequestException $e) {
                $this->logError($e->getMessage());
            } catch (InvalidSelectorException $e) {
                $this->logError($e->getMessage());
            } catch (\Throwable $e) {
                $this->logError($e->getMessage());
                sleep(30);
            }

            $count--;
            $pageNumber++;
        }
    }

    protected function getDocument($fullUrl)
    {
        while (true) {
            try {
                $dom = new Document($fullUrl, true);
                return $dom;
            } catch (\Throwable $e) {
                $this->logError($e->getMessage());
                sleep(30);
                continue;
            }
            break;
        }
    }

    protected function transformUrl($partner)
    {
        return $this->baseUrl . ltrim($partner->getAttribute('href'), './');
    }

    protected function getInfo($dom, $url = null)
    {
        try {
            $title = $dom->first('.partner-card-profile-header-title')->text();
            $link = $dom->first('.simple-link')->getAttribute('href');

            // Возвращаем строку с переносом строки
            return $this->generateId() . ", " . $title . ", " . $url . ", " . $link . PHP_EOL;
        } catch (InvalidSelectorException $e) {
            $this->logError($e->getMessage());
            return null;
        }
    }

    protected function isValidData($data)
    {
        if (is_null($data)) {
            return false;
        }

        $parts = array_map('trim', explode(',', $data));

        if (count($parts) !== 4) {
            return false;
        }

        list($id, $title, $url, $link) = $parts;

        return !empty($title) && !empty($link) && !empty($url);
    }

    protected function logError($message)
    {
        file_put_contents($this->errorLogFile, $message . PHP_EOL, FILE_APPEND);
    }
}
