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

    /*
     * Gets the account the current API key belongs to
     *
     * @return array $account The current user's Rebrandly account details
     */
    public function get()
    {
        $target = 'account';

        $account = $this->http->get($target);

        return $account;
    }
}

