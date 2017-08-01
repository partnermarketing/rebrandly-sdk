<?php

namespace Rebrandly\Test\Model;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Model\Link as LinkModel;

final class LinkModelTest extends TestCase
{
    public function setUp()
    {
        $this->createdLink = new LinkModel('TestDestination');
    }

    public function testCreate()
    {
        $this->assertInstanceOf(LinkModel::class, $this->createdLink);
    }

    public function testSetId()
    {
        $this->createdLink->setId('TestId');

        $this->assertSame($this->createdLink->getId(), 'TestId');
    }

    public function testSetTitle()
    {
        $this->createdLink->setTitle('TestTitle');

        $this->assertSame($this->createdLink->getTitle(), 'TestTitle');
    }

    public function testSetSlashtag()
    {
        $this->createdLink->setSlashtag('TestSlashtag');

        $this->assertSame($this->createdLink->getSlashtag(), 'TestSlashtag');
    }

    public function testSetShortUrl()
    {
        $this->createdLink->setShortUrl('TestShortUrl');

        $this->assertSame($this->createdLink->getShortUrl(), 'TestShortUrl');
    }

    public function testSetDomain()
    {
        $this->createdLink->setDomain(new DomainModel);

        $this->assertInstanceOf(DomainModel::class, $this->createdLink->getDomain());
    }

    public function testSetStatus()
    {
        $this->createdLink->setStatus('TestStatus');

        $this->assertSame($this->createdLink->getStatus(), 'TestStatus');
    }

    public function testSetCreatedAt()
    {
        $this->createdLink->setCreatedAt('TestCreatedAt');

        $this->assertSame($this->createdLink->getCreatedAt(), 'TestCreatedAt');
    }

    public function testSetUpdatedAt()
    {
        $this->createdLink->setUpdatedAt('TestUpdatedAt');

        $this->assertSame($this->createdLink->getUpdatedAt(), 'TestUpdatedAt');
    }

    public function testSetClicks()
    {
        $this->createdLink->setClicks('TestClicks');

        $this->assertSame($this->createdLink->getClicks(), 'TestClicks');
    }

    public function testSetLastClickAt()
    {
        $this->createdLink->setLastClickAt('TestLastClickAt');

        $this->assertSame($this->createdLink->getLastClickAt(), 'TestLastClickAt');
    }

    public function testSetFavourite()
    {
        $this->createdLink->setFavourite('TestFavourite');

        $this->assertSame($this->createdLink->getFavourite(), 'TestFavourite');
    }

    public function testSetForwardParameters()
    {
        $this->createdLink->setForwardParameters('TestForwardParameters');

        $this->assertSame($this->createdLink->getForwardParameters(), 'TestForwardParameters');
    }
}
