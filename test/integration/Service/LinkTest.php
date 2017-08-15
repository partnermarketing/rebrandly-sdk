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

    public function testQuickCreate()
    {
        $createdLink = $this->linkService->quickCreate('http://example.com/testQuickCreate');

        $this->assertInstanceOf(linkModel::class, $createdLink);
        $this->assertStringStartsWith('rebrand.ly/', $createdLink->getShortUrl());

        $this->linkService->delete($createdLink);
    }

    public function testFullCreate()
    {
        $linkModel = new linkModel('http://example.com/testFullCreate');
        $createdLink = $this->linkService->fullCreate($linkModel);

        $this->assertInstanceOf(linkModel::class, $createdLink);
        $this->assertStringStartsWith('rebrand.ly/', $createdLink->getShortUrl());

        $this->linkService->delete($createdLink);
    }

    public function testGetOne()
    {
        $createdLink = $this->linkService->quickCreate('http://example.com/testGetOne');

        $receivedLink = $this->linkService->getOne($createdLink);

        $this->assertInstanceOf(linkModel::class, $receivedLink);

        $this->linkService->delete($receivedLink);
    }

    public function testGetWithFilter()
    {
        $createdLinks = [];

        $i = 0;
        while ($i < 3) {
            $linkModel = new linkModel('http://example.com/testGetWithFilter' . $i);
            $linkModel->setFavourite(true);
            $createdLinks[$i] = $this->linkService->fullCreate($linkModel);

            $i++;
        }

        $receivedLinks = $this->linkService->search([
            'favourite' => true,
        ]);

        // To check that the filter worked, we filter the array to only keep
        // links which have been favourited, then compare that new array to the
        // original API call response. In theory they should be the same.
        $this->assertSame(
            array_filter($receivedLinks, function($link){return $link.favourite;}),
            $receivedLinks
        );

        // Clean up after ourselves
        foreach ($createdLinks as $link) {
            $this->linkService->delete($link);
        }
    }

    public function testPagination()
    {
        $createdLinks = [];

        $i = 0;
        while ($i < 3) {
            $linkModel = new linkModel('http://example.com/testPagination' . $i);
            $linkModel->setFavourite(true);
            $createdLinks[$i] = $this->linkService->fullCreate($linkModel);

            $i++;
        }
        $i = 0;

        while($i < 3) {
            $receivedLinks[$i] = $this->linkService->search([
                'offset' => 0,
                'limit' => $i,
            ]);
            $i++;
        }
        $this->assertTrue(count($receivedLinks) >= 3);

        // Clean up after ourselves
        foreach ($createdLinks as $link) {
            $this->linkService->delete($link);
        }
    }

    public function testDeleteById()
    {
        $linkModel = new LinkModel('http://example.com/testDeleteById');
        $createdLink = $this->linkService->fullCreate($linkModel);
        $deleteResult = $this->linkService->delete($createdLink->getId());

        // Now that we think we've deleted the link, let's try to get it
        $emptyLink = $this->linkService->getOne($createdLink->getId());

        $this->assertEquals($emptyLink, new LinkModel(''));
    }
}
