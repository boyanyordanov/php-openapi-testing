<?php

namespace OpenAPITestTools;


use JsonSchema\Validator;
use JSONSchemaFaker\Faker;
use OpenAPITestTools\JsonSchemaValidator;

trait OpenAPIAssertions
{
    /**
     * @param $path
     * @return null|string|string[]
     */
    protected function replacePathParam($path)
    {
        $path = preg_replace("/\{.+\}/", 1, $path);
        return $path;
    }

    protected function createFakeData($body)
    {
        return (array)Faker::fake($body);
    }

    /**
     * @param $path
     * @param $responseSchema
     * @return TestResponse
     */
    protected function assertGetResponseMatchesSchema($path, $responseSchema)
    {
        $path = $this->replacePathParam($path);
        $response = $this->get($path);
        $this->assertEquals(200, $this->, "Status code is not 200 for GET {$path}");
        $validator = new JsonSchemaValidator(json_decode($response->getContent()), $responseSchema);
        $error = $validator->error();
        $this->assertTrue($validator->passes(), "Failed to match schema to data on path: {$path}. Error: {$error}");
        return $response;
    }

    /**
     * @param $path
     * @param $bodySchema
     * @return TestResponse
     */
    protected function assertPostResponseMatchesSchema($path, $bodySchema)
    {
        $path = $this->replacePathParam($path);
        $data = $this->createFakeData($bodySchema);
        $response = $this->post($path, $data);

        $this->assertEquals(true, $response->isSuccessful(),
            "Status code {$response->getStatusCode()} is not successfull for POST {$path}");
        return $response;
    }

    /**
     * @param $path
     * @param Resource $schema
     */
    protected function assertEndpoint($path, $schema): void
    {
        if ($schema->hasMethod('GET')) {
            /** @var SchemaInterface $responseSchema */
            $responseSchema = $schema->getMethod('GET')->getResponse(200);
            $this->assertGetResponseMatchesSchema($path, json_decode(json_encode($responseSchema->getDefinition()->toArray())));
        }

        if ($schema->hasMethod('POST')) {
            $bodySchema = $schema->getMethod('POST')->getRequest()->getDefinition();

            $parsed = array_map(function($property) {
                return $property->toArray();
            }, $bodySchema->getProperties());

            $decoded = json_decode(json_encode(['type' => 'object', 'properties' => $parsed, 'required' => $bodySchema->getRequired()]));

            $response = $this->assertPostResponseMatchesSchema($path, $decoded);
        }
    }
}
