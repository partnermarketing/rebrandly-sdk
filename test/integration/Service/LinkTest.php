<?php

namespace Rebrandly\Test\Integration\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Link as LinkModel;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;

final class LinkServiceTest extends TestCase
{
    public function setUp()
    {
        $this->linkService = new LinkService('7d0bc889acf8492ea9dae7221d54b202');
    }

    // Because this actually uses fullCreate() under the hood, this is more
    // complete of a test than it looks.
    public function testQuickCreate()
    {
        $shortUrl = $this->linkService->quickCreate('http://example.com/testQuickCreate');

        $this->assertStringStartsWith('rebrand.ly/', $shortUrl);
    }

    public function testFullCreate()
    {
        $linkModel = new linkModel('http://example.com/testFullCreate');
        $createdLink = $this->linkService->fullCreate($linkModel);

        $this->assertInstanceOf(linkModel::class, $createdLink);
        $this->assertStringStartsWith('rebrand.ly/', $createdLink->getShortUrl());
    }
}
