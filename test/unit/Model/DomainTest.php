<?php

namespace Rebrandly\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Domain as DomainModel;

final class DomainModelTest extends TestCase
{
    public function setUp()
    {
        $this->createdDomain = new DomainModel();
    }

    public function testCreate()
    {
        $this->assertInstanceOf(DomainModel::class, $this->createdDomain);
    }
}
