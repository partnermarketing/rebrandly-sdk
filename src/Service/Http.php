<?php

namespace Rebrandly\Service;

class Http
{
    private $apiKey;

    private function startCurl($target)
    {
        $ch = curl_init('http://api.rebrandly.com/v1/' . $target);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->apiKey,
            'Content-Type: application/json',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return $ch;
    }

    private function finishCurl($ch)
    {
        $json = curl_exec($ch);

        $response = json_decode($json);

        curl_close($ch);

        return $response;
    }

    private function setApiKey($apiKey)
    {
        if (!preg_match('/^[0-z]{32}$', $apiKey)) {
            throw new InvalidArgumentException('Malformed API key. Expected 32 hexadecimal characters.');
        }
    }

    public function __construct($apiKey = '')
    {
        $this->apiKey = $apiKey;
    }

    public function post($target, $params = [])
    {
        $ch = $this->startCurl($target);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $response = $this->finishCurl($ch);

        return $response;
    }

    public function get($target, $params = [])
    {
        $queryString = http_build_query($params);

        $ch = $this->startCurl($target . $queryString);

        $response = $this->finishCurl($ch);

        return $response;
    }

    public function delete($target, $params = [])
    {
        $queryString = http_build_query($params);

        $ch = $this->startCurl($target . $queryString);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $reponse = $this->finishCurl($ch);

        return $response;
    }
}
