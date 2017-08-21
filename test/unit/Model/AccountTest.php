<?php

namespace Rebrandly\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Account as AccountModel;

final class AccountModelTest extends TestCase
{
    public function setUp()
    {
        $this->createdAccount = new AccountModel();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(AccountModel::class, $this->createdAccount);
    }

    public function testImport()
    {
        $accountArray = [
            'id' => 'TestId',
            'username' => 'TestUsername',
            'email' => 'TestEmail',
            'fullName' => 'TestFullName',
        ];

        $account = AccountModel::import($accountArray);

        $this->assertInstanceOf(AccountModel::class, $account);
        $this->assertEquals('TestUsername', $account->getUsername());
    }
}
