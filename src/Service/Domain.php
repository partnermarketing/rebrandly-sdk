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

    public function getOne($id)
    {
        $target = 'domains/' . $id;

        $domain = $this->http->get($target);

        return $domain;
    }

    public function search($params)
    {
        $target = 'domains/';

        $domains = $this->http->get($target, $params);

        return $domains;
    }

    public function count($params)
    {
        $target = 'domains/count';

        $count = $this->http->get($target, $params);

        return $count;
    }
}
