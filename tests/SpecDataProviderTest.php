<?php

namespace OpenAPITestTools\Tests;

use OpenAPITestTools\SpecDataProvider;
use PHPUnit\Framework\TestCase;
use PSX\Api\Resource;

class SpecDataProviderTest extends TestCase
{
    /** @test */
    public function it_generates_an_array_of_paths_and_response_schemas()
    {
        $provider = new SpecDataProvider(__DIR__ . '/petstore.yaml');
        $testCases = $provider->getTestCases();
        $this->assertTrue(is_array($testCases), 'The data provider has to be an array or iterator');
        $this->assertArrayHasKey('/pets', $testCases, 'The data provider should contain all endpoints');
        $this->assertArrayHasKey('/pets/{petId}', $testCases, 'The data provider should contain all endpoints');
        $this->assertInstanceOf(Resource::class, $testCases['/pets'][1], 'The data provider values should be Resource objects');
    }
}
