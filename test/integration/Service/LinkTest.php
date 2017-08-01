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

    public function testQuickCreateLink()
    {
        $shortUrl = $this->linkService->quickCreate('http://example.com/longurlfortesting');

        $this->assertStringStartsWith('rebrand.ly/', $shortUrl);
    }
}
