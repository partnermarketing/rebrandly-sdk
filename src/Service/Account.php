<?php

namespace Rebrandly\Service;

use Rebrandly\Service\Http;

class Account
{
    private $http;

    public function __construct__($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    public function get()
    {
        $target = 'account';

        $account = $this->http->get($target);

        return $account;
    }
}

