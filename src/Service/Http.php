<?php

namespace Rebrandly\Service;

class Http
{
    const APIROOT = 'http://api.rebrandly.com/v1/';

    private $apiKey;

    /**
     * Initialises a curl handle with defaults we always need
     *
     * @param string $target The API endpoint being used
     *
     * @return curl $ch
     */
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

    /**
     * Fires the request, parses the response, and closes the curl handle
     *
     * @param curl
     *
     * @return array $response The JSON-decoded response from the API
     */
    private function finishCurl($ch)
    {
        $json = curl_exec($ch);

        $response = json_decode($json, true);

        $curlInfo = curl_getinfo($ch);

        curl_close($ch);

        return $response;
    }

    /**
     * Transforms an associative array of parameters into a query string.
     * Includes special cases to handle booleans, as the Rebrandly API doesn't
     * accept PHP's default string casts of bools as bools, and they need
     * rewriting into 'true' or 'false' strings.
     *
     * @param array
     *
     * @return string
     */
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

    /**
     * @param string $apiKey An API key as provided from
     * https://www.rebrandly.com/api-settings
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $target API endpoint URL
     *
     * @param array|null $params Any data required to be sent as POST body
     *
     * @return array $response Parsed response from the API
     */
    public function post($target, $params = [])
    {
        $ch = $this->startCurl($target);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $response = $this->finishCurl($ch);

        return $response;
    }

    /**
     * @param string $target API endpoint URL
     *
     * @param array|null $params Any data required to be sent as a query string
     *
     * @return array $response Parsed response from the API
     */
    public function get($target, $params = [])
    {
        $queryString = $this->buildQueryString($params);

        $ch = $this->startCurl($target . '?' . $queryString);

        $response = $this->finishCurl($ch);

        return $response;
    }

    /**
     * @param string $target API endpoint URL
     *
     * @param array|null $params Any data required to be sent as a query string
     *
     * @return array $response Parsed response from the API
     */
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
