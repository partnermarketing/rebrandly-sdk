<?php

namespace Rebrandly\Test\Integration\Service;

use PHPUnit\Framework\TestCase;
use Rebrandly\Model\Domain as DomainModel;
use Rebrandly\Service\Domain as DomainService;
use Rebrandly\Service\Http;

final class DomainServiceTest extends TestCase
{
    /*
     * Loads a valid API key into the domain management service to use for
     * testing
     *
     * TODO: Remove the hard-coded API key. Move it into an environment var or
     * something.
     */
    public function setUp()
    {
        $this->domainService = new DomainService('7d0bc889acf8492ea9dae7221d54b202');
    }

    /*
     * Tests domain counting functionality. Because the user might not have
     * configured any domains and the API doesn't allow us to create domains,
     * we can't guarantee the existence of any domains, so this is the only
     * behaviour we can test.
     */
    public function testCount()
    {
        $count = $this->domainService->count()['count'];

        $this->assertInternalType('int', $count);
    }
}
