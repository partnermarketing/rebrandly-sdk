<?php

namespace Rebrandly\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Link as LinkModel;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;

final class LinkServiceTest extends TestCase
{
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

    public function testFullCreateLink()
    {
        $linkModel = new LinkModel('TestDestination');

        // In the real world, this endpoint will return a 1-length numeric
        // array containing an associative array. This is because the link
        // creation endpoint can actually accept a POST with several links, so
        // the response can be arbitrarily long. In this case, we're testing
        // generating one link, hence the nested structure.
        $this->httpMock->method('get')->willReturn([[
            'shortUrl' => 'TestShortUrl',
            'slashtag' => 'TestSlashtag',
            'title' => 'TestTitle',
            'favourite' => true,
        ]]);

        $createdLink = $this->linkService->fullCreate($linkModel);

        $this->assertInstanceOf(LinkModel::class, $createdLink);
        $this->assertSame($createdLink->getDestination(), 'TestDestination');
        $this->assertSame($createdLink->getShortUrl(), 'TestShortUrl');
        $this->assertSame($createdLink->getSlashtag(), 'TestSlashtag');
        $this->assertSame($createdLink->getTitle(), 'TestTitle');
        $this->assertTrue($createdLink->getFavourite());
    }

    public function testQuickCreateLink()
    {
        $this->httpMock->method('get')->willReturn([[
            'shortUrl' => 'TestShortUrl',
        ]]);

        $shortUrl = $this->linkService->quickCreate('TestDestination');
        $this->assertSame($shortUrl, 'TestShortUrl');
    }
}
