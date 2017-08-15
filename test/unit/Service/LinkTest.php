<?php

namespace Rebrandly\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Link as LinkModel;
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
        $linkModel = new LinkModel('TestDestination');

        $this->setResponse('post', [
            'destination' => 'TestDestination',
        ]);

        $createdLink = $this->linkService->fullCreate($linkModel);

        $this->assertInstanceOf(LinkModel::class, $createdLink);
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
        // First we need to generate a mocked link to test against, so just run
        // through the usual steps to generate a linkModel.
        $linkModel = new LinkModel('TestDestination');

        $this->setResponse('post');

        // Since we're testing the ability to get full details for a link given
        // the ID, we now extract the ID from our link to send to the mocked
        // link details endpoint
        $createdLink = $this->linkService->fullCreate($linkModel);
        $linkId = $createdLink->getId();

        $this->setResponse('get');

        $readLink = $this->linkService->getOne($linkId);

        $this->assertInstanceOf(LinkModel::class, $readLink);
        $this->assertEquals($readLink, $createdLink);
    }
}
