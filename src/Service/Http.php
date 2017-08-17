<?php

namespace Rebrandly\Service;

class Http
{
    const APIROOT = 'http://api.rebrandly.com/v1/';

    private $apiKey;

    private function startCurl($target)
    {
        $ch = curl_init(self::APIROOT . $target);

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

        $response = json_decode($json, true);

        $curlInfo = curl_getinfo($ch);

        curl_close($ch);

        return $response;
    }

    private function buildQueryString($params = [])
    {
        $newParams = [];
        foreach ($params as $key => $value) {
            switch (gettype($value)) {
            case boolean:
                $newParams[$key] = $value ? 'true' : 'false';
                break;
            default:
                $newParams[$key] = $value;
            }
        }

        return http_build_query($newParams);
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
        $queryString = $this->buildQueryString($params);

        $ch = $this->startCurl($target . '?' . $queryString);

        $response = $this->finishCurl($ch);

        return $response;
    }

    public function delete($target, $params = [])
    {
        $queryString = $this->buildQueryString($params);

        $target .= '?' . $queryString;

        $ch = $this->startCurl($target);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $response = $this->finishCurl($ch);

        return $response;
    }
}
