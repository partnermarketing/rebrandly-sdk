<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Service\Http;

class Domain
{
    private $http;

    public function __construct__($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    /*
     * Gets a domain given its ID as provided by Rebrandly
     *
     * @param string $id ID of the domain to fetch
     *
     * @return array $domain The complete domain as returned from the API
     */
    public function getOne($id)
    {
        $target = 'domains/' . $id;

        $domain = $this->http->get($target);

        return $domain;
    }

    /*
     * Gets domains based on search criteria, with sorting
     *
     * @param array $params A list of criteria for filtering and sorting results
     *
     * @return array $domains A list of domains matching the given criteria
     */
    public function search($params)
    {
        $target = 'domains/';

        $domains = $this->http->get($target, $params);

        return $domains;
    }

    /*
     * Counts domains, with optional filters
     *
     * @param array $filters Fields to restrict the count by
     *
     * @return array $domains A list of domains matching the given criteria
     */
    public function count($filters)
    {
        $target = 'domains/count';

        $count = $this->http->get($target, $filters);

        return $count;
    }
}
