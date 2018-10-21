<?php

namespace OpenAPITestTools;


use PSX\Api\Parser\OpenAPI;
use Symfony\Component\Yaml\Yaml;

class SpecDataProvider
{

    protected $specification;

    public function __construct($schema)
    {
        $spec = Yaml::parseFile($schema);
        $this->specification = str_replace('\/', '/', json_encode($spec));

    }

    public function getTestCases()
    {
        $schema = new OpenAPI();
        $parsed = $schema->parseAll($this->specification);

        $result = [];
        foreach($parsed as $resource) {
            /** @var $resource Resource */
            $result[$resource->getPath()] = [$resource->getPath(), $resource];
        }

        return $result;
    }

}
