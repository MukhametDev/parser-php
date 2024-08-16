<?php

namespace Framework;
class HTTPClient
{
    private $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    public function get($url): ?string
    {
        try {
            $response = $this->client->request('GET', $url);
            return $response->getBody()->getContents();
        } catch (Exception $e) {
            ErrorHandler::logError($e->getMessage());
            return null;
        }
    }

}