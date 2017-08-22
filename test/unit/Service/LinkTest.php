<?php

namespace Rebrandly\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Link as LinkModel;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;

final class LinkServiceTest extends TestCase
{
    private $defaultFakeApiResponse = [
        'id' => 'TestId',
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
        $link = new LinkModel();
        $link->setDestination('TestDestination');

        $this->setResponse('post', $link->export());

        $createdLink = $this->linkService->fullCreate($link);

        $this->assertSame('TestDestination', $createdLink->getDestination());
        $this->assertSame('TestShortUrl', $createdLink->getShortUrl());
        $this->assertSame('TestSlashtag', $createdLink->getSlashtag());
        $this->assertSame('TestTitle', $createdLink->getTitle());
        $this->assertFalse($createdLink->getFavourite());
    }

    public function testQuickCreateLink()
    {
        $this->setResponse('post');

        $createdLink = $this->linkService->quickCreate('TestDestination');
        $this->assertSame($createdLink->getShortUrl(), 'TestShortUrl');
    }

    public function testReadLink()
    {
        $this->setResponse('post');
        $this->setResponse('get');

        // First we need to generate a mocked link to test against, so just run
        // through the usual steps to generate a linkModel.
        $createdLink = $this->linkService->quickCreate('TestDestination');

        // Since we're testing the ability to get full details for a link given
        // the ID, we now extract the ID from our link to send to the mocked
        // link details endpoint
        $linkId = $createdLink->getId();

        $readLink = $this->linkService->getOne($linkId);
        $this->assertEquals($readLink, $createdLink);
    }
}
