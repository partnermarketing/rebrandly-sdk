<?php

namespace Rebrandly\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;

final class LinkServiceTest extends TestCase
{
    private $defaultFakeApiResponse = [
        'shortUrl' => 'TestShortUrl',
        'slashtag' => 'TestSlashtag',
        'title' => 'TestTitle',
        'favourite' => false,
    ];

    public function setUp()
    {
        $this->httpMock = $this->mockCreateHttp();
        $this->linkService = $this->reflectLinkService($this->httpMock);
    }

    private function mockCreateHttp()
    {
        $httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->setMethods(['post', 'get', 'delete'])
            ->getMock();
        return $httpMock;
    }

    private function reflectLinkService($httpMock)
    {
        $reflectionLinkService = new \ReflectionClass(LinkService::class);

        $link = $reflectionLinkService->newInstanceWithoutConstructor();

        $reflectionHttp = $reflectionLinkService->getProperty('http');
        $reflectionHttp->setAccessible(true);
        $reflectionHttp->setValue($link, $httpMock);

        return $link;
    }

    private function setResponse($method, $overrides = [])
    {
        $response = array_replace($this->defaultFakeApiResponse, $overrides);

        $this->httpMock->method($method)->willReturn($response);
    }

    public function testFullCreateLink()
    {
        $link = [
            'destination' => 'TestDestination',
        ];

        $this->setResponse('post', $link);

        $createdLink = $this->linkService->fullCreate($link);

        $this->assertSame('TestDestination', $createdLink['destination']);
        $this->assertSame('TestShortUrl', $createdLink['shortUrl']);
        $this->assertSame('TestSlashtag', $createdLink['slashtag']);
        $this->assertSame('TestTitle', $createdLink['title']);
        $this->assertFalse($createdLink['favourite']);
    }

    public function testQuickCreateLink()
    {
        $this->setResponse('post');

        $createdLink = $this->linkService->quickCreate('TestDestination');
        $this->assertSame($createdLink['shortUrl'], 'TestShortUrl');
    }

    public function testReadLink()
    {
        // First we need to generate a mocked link to test against, so just run
        // through the usual steps to generate a linkModel.
        $link = [
            'destination' => 'TestDestination',
        ];

        $this->setResponse('post');

        // Since we're testing the ability to get full details for a link given
        // the ID, we now extract the ID from our link to send to the mocked
        // link details endpoint
        $createdLink = $this->linkService->fullCreate($link);
        $linkId = $createdLink->id;

        $this->setResponse('get');

        $readLink = $this->linkService->getOne($linkId);

        $this->assertEquals($readLink, $createdLink);
    }
}
