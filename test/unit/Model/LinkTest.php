<?php

namespace Rebrandly\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Model\Link as LinkModel;

final class LinkModelTest extends TestCase
{
    public function setUp()
    {
        $this->createdLink = new LinkModel();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(LinkModel::class, $this->createdLink);
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

    public function testSetDomain()
    {
        $this->createdLink->setDomain(new DomainModel);

        $this->assertInstanceOf(DomainModel::class, $this->createdLink->getDomain());
    }
}
