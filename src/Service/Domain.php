<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Service\Http;

/**
 * Domain Service
 *
 * Handles API requests for all Domain related actions
 */
class Domain
{
    /**
     * @var Http $http HTTP helper class shared by all Rebrandly SDK services
     */
    private $http;

    public function __construct($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    /**
     * Gets full details of a single domain given its ID
     *
     * @param string $domainId the ID of the domain, as provided on creation by
     *    Rebrandly
     *
     * @return domainModel $domain A populated domain as returned from the API
     */
    public function getOne($domainId)
    {
        $target = 'domains/' . $domainId;

        $response = $this->http->get($target);

        $domain = DomainModel::import($response);

        return $domain;
    }

     /**
     * Search for domains meeting some criteria, with sorting controls
     *
     * @param array $filters A list of parameters to filter and sort by
     *
     * @return DomainModel[] $domains A list of domains that meet the given criteria
     */
    public function search($filters = [])
    {
        $target = 'domains/';

        $response = $this->http->get($target, $filters);

        $domains = [];
        foreach ($response as $domainArray) {
            $domain = DomainModel::import($domainArray);
            array_push($domains, $domain);
        }

        return $domains;
    }

    /**
     * Counts domains, with optional filters
     *
     * @param array $filters Fields to restrict the count by
     *
     * @return array $domains A list of domains matching the given criteria
     */
    public function count($filters = [])
    {
        $target = 'domains/count';

        $count = $this->http->get($target, $filters);

        return $count;
    }
}
