<?php

namespace Rebrandly\Test\Integration\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Account as AccountModel;
use Rebrandly\Service\Account as AccountService;
use Rebrandly\Service\Http;

final class AccountServiceTest extends TestCase
{
    /*
     * Loads a valid API key into the account management service to use for
     * testing
     *
     * TODO: Remove the hard-codede API key. Move it into an environment var or
     * something.
     */
    public function setUp()
    {
        $this->accountService = new AccountService('7d0bc889acf8492ea9dae7221d54b202');
    }

    /*
     * Tests getting details for a single account by creating a account and then
     * attempting to retrieve it by its ID.
     */
    public function testGet()
    {
        $receivedAccount = $this->accountService->get();

        $this->assertInstanceOf(AccountModel::class, $receivedAccount);
    }
}
