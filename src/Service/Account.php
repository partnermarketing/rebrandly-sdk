<?php

namespace Rebrandly\Service;

use Rebrandly\Model\Account as AccountModel;
use Rebrandly\Service\Http;

/**
 * Account Service
 *
 * Handles API requests for all account related actions
 */
class Account
{
    private $http;

    public function __construct($apiKey)
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

        $account = AccountModel::import($response);

        return $account;
    }
}

