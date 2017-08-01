<?php

namespace Rebrandly\Test\Model;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Model\Link as LinkModel;

final class LinkModelTest extends TestCase
{
    public function testCreate()
    {
        $createdLink = new LinkModel('TestDestination');
        $this->assertInstanceOf(LinkModel::class, $createdLink);
        $this->assertSame($createdLink->getDestination(), 'TestDestination');
    }

    public function testSetId()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setId('TestId');

        $this->assertSame($createdLink->getId(), 'TestId');
    }

    public function testSetTitle()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setTitle('TestTitle');

        $this->assertSame($createdLink->getTitle(), 'TestTitle');
    }

    public function testSetSlashtag()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setSlashtag('TestSlashtag');

        $this->assertSame($createdLink->getSlashtag(), 'TestSlashtag');
    }

    public function testSetShortUrl()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setShortUrl('TestShortUrl');

        $this->assertSame($createdLink->getShortUrl(), 'TestShortUrl');
    }

    public function testSetDomain()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setDomain(new DomainModel);

        $this->assertInstanceOf(DomainModel::class, $createdLink->getDomain());
    }

    public function testSetStatus()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setStatus('TestStatus');

        $this->assertSame($createdLink->getStatus(), 'TestStatus');
    }

    public function testSetCreatedAt()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setCreatedAt('TestCreatedAt');

        $this->assertSame($createdLink->getCreatedAt(), 'TestCreatedAt');
    }

    public function testSetUpdatedAt()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setUpdatedAt('TestUpdatedAt');

        $this->assertSame($createdLink->getUpdatedAt(), 'TestUpdatedAt');
    }

    public function testSetClicks()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setClicks('TestClicks');

        $this->assertSame($createdLink->getClicks(), 'TestClicks');
    }

    public function testSetLastClickAt()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setLastClickAt('TestLastClickAt');

        $this->assertSame($createdLink->getLastClickAt(), 'TestLastClickAt');
    }

    public function testSetFavourite()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setFavourite('TestFavourite');

        $this->assertSame($createdLink->getFavourite(), 'TestFavourite');
    }

    public function testSetForwardParameters()
    {
        $createdLink = new LinkModel('TestDestination');
        $createdLink->setForwardParameters('TestForwardParameters');

        $this->assertSame($createdLink->getForwardParameters(), 'TestForwardParameters');
    }
}
