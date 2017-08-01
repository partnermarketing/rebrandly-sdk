<?php

namespace Rebrandly\Test\Service;

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
            ->setMethods(['send'])
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

        $this->httpMock->method('send')->willReturn([
            'shortUrl'  => 'TestShortUrl',
            'slashtag'  => 'TestSlashtag',
            'title'     => 'TestTitle',
            'favourite' => true,
        ]);

        $createdLink = $this->linkService->fullCreate($linkModel);

        $this->assertInstanceOf(LinkModel::class, $createdLink);
        $this->assertSame($createdLink->getDestination(),  'TestDestination');
        $this->assertSame($createdLink->getShortUrl(),     'TestShortUrl');
        $this->assertSame($createdLink->getSlashtag(),     'TestSlashtag');
        $this->assertSame($createdLink->getTitle(),        'TestTitle');
        $this->assertTrue($createdLink->getFavourite());
    }

    public function testQuickCreateLink()
    {
        $this->httpMock->method('send')->willReturn([
            'shortUrl' => 'http://short',
        ]);

        $shortUrl = $this->linkService->quickCreate('http://long');
        $this->assertSame($shortUrl, 'http://short');
    }
}
