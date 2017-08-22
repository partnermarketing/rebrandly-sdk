<?php

namespace Rebrandly\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Account as AccountModel;
use Rebrandly\Service\Http;
use Rebrandly\Service\Account as AccountService;

final class AccountServiceTest extends TestCase
{
    private $defaultFakeApiResponse = [
        'id' => 'TestId',
        'username' => 'TestUsername',
        'email' => 'TestEmail',
        'fullname' => 'TestFullName',
        'avatarUrl' => 'TestAvatarUrl',
        'createdAt' => '1503070000',
    ];

    public function setUp()
    {
        $this->httpMock = $this->mockCreateHttp();
        $this->accountService = $this->reflectAccountService($this->httpMock);
    }

    private function mockCreateHttp()
    {
        $httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['post', 'get', 'delete'])
            ->getMock();
        return $httpMock;
    }

    private function reflectAccountService($httpMock)
    {
        $reflectionAccountService = new \ReflectionClass(AccountService::class);

        $account = $reflectionAccountService->newInstanceWithoutConstructor();

        $reflectionHttp = $reflectionAccountService->getProperty('http');
        $reflectionHttp->setAccessible(true);
        $reflectionHttp->setValue($account, $httpMock);

        return $account;
    }

    private function setResponse($method, $overrides = [])
    {
        $response = array_replace($this->defaultFakeApiResponse, $overrides);

        $this->httpMock->method($method)->willReturn($response);
    }

    public function testGet()
    {
        $this->setResponse('get');

        // This looks different from other get tests as the account is
        // identified by the current API key, rather than by an ID in the URL
        $testAccount = $this->accountService->get();

        $this->assertInstanceOf(AccountModel::class, $testAccount);
    }
}
