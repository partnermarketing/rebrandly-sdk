<?php

namespace Rebrandly\Test\Integration\Service;

use PHPUnit\Framework\TestCase;
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

        $this->assertStringStartsWith('rebrand.ly/', $createdLink['shortUrl']);

        $this->linkService->delete($createdLink);
    }

    public function testFullCreate()
    {
        $destination = 'http://example.com/testFullCreate';

        $link = [
            'destination' => $destination,
        ];

        $createdLink = $this->linkService->fullCreate($link);

        $this->assertStringStartsWith('rebrand.ly/', $createdLink['shortUrl']);

        $this->linkService->delete($createdLink['id']);
    }

    public function testGetOne()
    {
        $destination = 'http://example.com/testGetOne';
        $createdLink = $this->linkService->quickCreate($destination);

        $receivedLink = $this->linkService->getOne($createdLink['id']);

        $this->assertEquals($destination, $receivedLink['destination']);

        $this->linkService->delete($receivedLink);
    }

    public function testSearch()
    {
        $createdLinks = [];

        for ($i=0; $i<3; $i++) {
            $link = [
                'destination' => 'http://example.com/testGetWithFilter' . $i,
                'favourite' => true,
            ];

            $createdLinks[$i] = $this->linkService->fullCreate($link);
        }

        $receivedLinks = $this->linkService->search([
            'favourite' => true,
        ]);

        // To check that the filter worked, we filter the array to only keep
        // links which have been favourited, then compare that new array to the
        // original API call response. In theory they should be the same.
        $this->assertSame(
            array_filter($receivedLinks, function($link){return $link['favourite'];}),
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

        for ($i=0; $i<3; $i++) {
            $link = [
                'destination' => 'http://example.com/testPagination' . $i,
                'favourite' => true,
            ];

            $createdLinks[$i] = $this->linkService->fullCreate($link);
        }

        for ($i=0; $i<3; $i++) {
            $receivedLinks[$i] = $this->linkService->search([
                'offset' => $i,
                'limit' => 0,
            ]);
        }
        $this->assertTrue(count($receivedLinks) >= 3);

        // Clean up after ourselves
        foreach ($createdLinks as $link) {
            $this->linkService->delete($link['id']);
        }
    }

    public function testDeleteById()
    {
        $createdLink = $this->linkService->quickCreate('http://example.com/testDeleteById');
        $deleteResult = $this->linkService->delete($createdLink['id']);

        // Now that we think we've deleted the link, let's try to get it
        $emptyLink = $this->linkService->getOne($createdLink['id']);
    }
}
