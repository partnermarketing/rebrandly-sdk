<?php

namespace Rebrandly\Test\Integration\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Link as LinkModel;
use Rebrandly\Service\Http;
use Rebrandly\Service\Link as LinkService;

final class LinkServiceTest extends TestCase
{
    /*
     * Loads a valid API key into the link management service to use for
     * testing
     *
     * TODO: Remove the hard-codede API key. Move it into an environment var or
     * something.
     */
    public function setUp()
    {
        $this->linkService = new LinkService('7d0bc889acf8492ea9dae7221d54b202');
    }

    /*
     * Tests quick-creating a link by checking that the creation response
     * includes the shortUrl, a field we should always expect to get back on a
     * successful submission
     */
    public function testQuickCreate()
    {
        $createdLink = $this->linkService->quickCreate('http://example.com/testQuickCreate');

        $this->assertStringStartsWith('rebrand.ly/', $createdLink->getShortUrl());

        $this->linkService->delete($createdLink->getId());
    }

    /*
     * Tests creating a link by checking that the creation response includes
     * the shortUrl, a field we should always expect to get back on a
     * successful submission
     */
    public function testFullCreate()
    {
        $destination = 'http://example.com/testFullCreate';

        $link = new LinkModel();
        $link->setDestination($destination);

        $createdLink = $this->linkService->fullCreate($link);

        $this->assertStringStartsWith('rebrand.ly/', $createdLink->getShortUrl());

        $this->linkService->delete($createdLink->getId());
    }

    /*
     * Tests getting details for a single link by creating a link and then
     * attempting to retrieve it by its ID.
     */
    public function testGetOne()
    {
        $destination = 'http://example.com/testGetOne';
        $createdLink = $this->linkService->quickCreate($destination);

        $receivedLink = $this->linkService->getOne($createdLink->getId());

        $this->assertEquals($destination, $receivedLink->getDestination());

        $this->linkService->delete($receivedLink->getId());
    }

    /*
     * Tests search functionality by creating three links that are flagged as
     * favourites, then retrieving three links with a filter set to only
     * retrieve favourites.
     *
     * We then perform the inverse test to make sure that even in cases where
     * the real-world data living in Rebrandly would cause one test to pass
     * coincidentally, we can still catch issues.
     */
    public function testSearch()
    {
        $destination = 'http://example.com/testGetWithFilter';
        $favouriteLink = new LinkModel;
        $favouriteLink->setDestination($destination . 'IsFavourite');
        $favouriteLink->setFavourite(true);
        $notFavouriteLink = new LinkModel;
        $notFavouriteLink->setDestination($destination . 'IsFavourite');
        $notFavouriteLink->setFavourite(false);

        $favouriteLink = $this->linkService->fullCreate($favouriteLink);
        $notFavouriteLink = $this->linkService->fullCreate($notFavouriteLink);

        $receivedFavouriteLink = $this->linkService->search([
            'favourite' => true,
            'limit' => 1,
        ])[0];
        $receivedNotFavouriteLink = $this->linkService->search([
            'favourite' => false,
            'limit' => 1,
        ])[0];

        $this->assertTrue($receivedFavouriteLink->getFavourite());
        $this->assertFalse($receivedNotFavouriteLink->getFavourite());

        // Clean up after ourselves
        $this->linkService->delete($favouriteLink->getId());
        $this->linkService->delete($notFavouriteLink->getId());
    }

    /*
     * Tests pagination by creating three links and then requesting one link at
     * a time on different offsets.
     */
    public function testPagination()
    {
        $createdLinks = [];

        for ($i=0; $i<3; $i++) {
            $createdLinks[$i] = $this->linkService->quickCreate('http://example.com/testPagination' . $i);
        }

        $receivedLinks = [];
        for ($i=0; $i<3; $i++) {
            $receivedLinks = array_merge($receivedLinks, $this->linkService->search([
                'offset' => $i,
                'limit' => 1,
            ]));
        }

        $this->assertEquals(3, count($receivedLinks));

        // Now check that we actually got three different links back by
        // checking that no entry in the results is identical to any other
        for ($i=0; $i<3; $i++) {
            $a = $receivedLinks[$i];
            $b = $receivedLinks[($i+1)%3];
            $this->assertNotEquals($a, $b);
        }

        // Clean up after ourselves
        foreach ($createdLinks as $link) {
            $this->linkService->delete($link->getId());
        }
    }

    /*
     * Tests deletion by creating a link, deleting it, then trying to retrieve
     * it by its ID
     *
     * TODO: Finish this.
     */
    public function testDeleteById()
    {
        $createdLink = $this->linkService->quickCreate('http://example.com/testDeleteById');
        $deleteResult = $this->linkService->delete($createdLink->getId());
    }
}
