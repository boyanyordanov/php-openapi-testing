<?php

namespace OpenAPITestTools\Tests;

use OpenAPITestTools\OpenAPIAssertions;
use PHPUnit\Framework\TestCase;


class OpenAPIAssertionsTest extends TestCase
{
    /**
     * @var OpenAPIAssertions
     */
    protected $assertions;

    public function setUp()
    {
        $this->assertions = new class {
            use OpenAPIAssertions;
        };
    }

    /** @test */
    public function it_tests_an_endpoint()
    {
        $this->assertions->assertEndpoint('/pets');
    }
}
