<?php

namespace Rebrandly\Service;

class Http
{
    private $apiKey;

    public function setApiKey($apiKey)
    {
        if (!preg_match('/^[0-z]{32}$', $apiKey)) {
            throw new InvalidArgumentException('Malformed API key. Expected 32 hexadecimal characters.');
        }
    }

    public function __construct($apiKey = '')
    {
        $this->apiKey = $apiKey;
    }

    public function send($target = '', $payload = [])
    {
        $ch = curl_init('http://api.rebrandly.com/v1/' . $target);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }
}
