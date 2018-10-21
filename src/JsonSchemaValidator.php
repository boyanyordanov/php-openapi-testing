<?php
/**
 * Created by PhpStorm.
 * User: boyan
 * Date: 10/22/18
 * Time: 2:05 AM
 */

namespace OpenAPITestTools;


use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema;

class JsonSchemaValidator
{

    private $error;

    public function __construct($schema, $response)
    {
        $validator = Schema::import($schema);
        try {
            $validator->in(json_decode($response->getContent()));
        } catch (InvalidValue $e) {
            $this->error = $e->getMessage();
        }
    }

    public function passes()
    {
        return isset($this->error);
    }

    public function error()
    {
        return $this->error ? $this->error : 'No errors';
    }

}
