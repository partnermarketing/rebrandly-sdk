<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Account as AccountModel;
use Rebrandly\Service\Http;

class Account
{
    private $http;

    public function __construct__($apiKey)
    {
        $this->http = new Http($apiKey);
    }

    /**
     * Gets the account the current API key belongs to
     *
     * @return AccountModel $account The current user's Rebrandly account details
     */
    public function get()
    {
        $target = 'account';

        $response = $this->http->get($target);

        $account = new AccountModel;
        $account->import($response);

        return $account;
    }
}

