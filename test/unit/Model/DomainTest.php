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

    public function testImport()
    {
        $domainArray = [
            'id' => 'TestId',
            'fullName' => 'TestFullName',
            'topLevelDomain' => 'TestTopLevelDomain',
        ];

        $domain = DomainModel::import($domainArray);

        $this->assertInstanceOf(DomainModel::class, $domain);
        $this->assertEquals('TestFullName', $domain->getFullName());
    }
}
