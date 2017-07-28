<?php

namespace Rebrandly\Test\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;
use Rebrandly\Model\Link as LinkModel;

final class LinkServiceTest extends TestCase
{
    private function mockQuickCreateHttp()
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
        $httpMock = $this->mockQuickCreateHttp();

        $linkService = $this->reflectLinkService($httpMock);

        $linkModel = new LinkModel('http://long');

        $httpMock->method('send')->willReturn([
            'shortUrl' => 'http://short',
        ]);

        $createdLink = $linkService->fullCreate($linkModel);

        $this->assertInstanceOf(LinkModel::class, $createdLink);
        $this->assertEquals($createdLink->getDestination(), 'http://long');
        $this->assertEquals($createdLink->getShortUrl(), 'http://short');
    }

    public function testQuickCreateLink()
    {
        $this->markTestIncomplete('');
        $httpMock = $this->mockQuickCreateHttp();

        $linkService = $this->reflectLinkService($httpMock);

        $shortUrl = $link->quickCreate('http://example.com');

        $this->assertSame($shortUrl, 'http://short');
    }
}
