<?php

namespace Rebrandly\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Service\Http;
use Rebrandly\Service\Domain as DomainService;

final class DomainServiceTest extends TestCase
{
    private $defaultFakeApiResponse = [
        'id' => 'TestId',
        'fullName' => 'TestFullName',
        'topLevelDomain' => 'TestTopLevelDomain',
        'createdAt' => '1503070000',
        'updatedAt' => '1503071111',
        'type' => 'TestType',
        'active' => true,
    ];

    public function setUp()
    {
        $this->httpMock = $this->mockCreateHttp();
        $this->domainService = $this->reflectDomainService($this->httpMock);
    }

    private function mockCreateHttp()
    {
        $httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['post', 'get', 'delete'])
            ->getMock();
        return $httpMock;
    }

    private function reflectDomainService($httpMock)
    {
        $reflectionDomainService = new \ReflectionClass(DomainService::class);

        $domain = $reflectionDomainService->newInstanceWithoutConstructor();

        $reflectionHttp = $reflectionDomainService->getProperty('http');
        $reflectionHttp->setAccessible(true);
        $reflectionHttp->setValue($domain, $httpMock);

        return $domain;
    }

    private function setResponse($method, $overrides = [])
    {
        $response = array_replace($this->defaultFakeApiResponse, $overrides);

        $this->httpMock->method($method)->willReturn($response);
    }

    public function testGetOne()
    {
        $this->setResponse('get');

        $testDomain = $this->domainService->getOne('TestId');

        $this->assertInstanceOf(DomainModel::class, $testDomain);
    }
}
