<?php

namespace Rebrandly\Test\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;
use Rebrandly\Model\Link as LinkModel;

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
        $linkModel = new LinkModel('http://long');

        $this->httpMock->method('send')->willReturn([
            'shortUrl' => 'http://short',
        ]);

        $createdLink = $this->linkService->fullCreate($linkModel);

        $this->assertInstanceOf(LinkModel::class, $createdLink);
        $this->assertEquals($createdLink->getDestination(), 'http://long');
        $this->assertEquals($createdLink->getShortUrl(), 'http://short');
    }

    public function testQuickCreateLink()
    {
        $this->httpMock->method('send')->willReturn([
            'shortUrl' => 'http://short',
        ]);

        $shortUrl = $this->linkService->quickCreate('http://long');
        $this->assertSame($shortUrl, 'http://short');
    }

    public function testCreateFavouriteLink()
    {
        $linkModel = new LinkModel('doesntmatter');

        $this->httpMock->method('send')->willReturn([
            'favourite' => true,
        ]);

        $createdLink = $this->linkService->fullCreate($linkModel);
        $isFavourite = $createdLink->getFavourite();

        $this->assertTrue($isFavourite);
    }
}
