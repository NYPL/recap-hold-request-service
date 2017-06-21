<?php
namespace NYPL\Services;

use NYPL\Starter\SwaggerGenerator;

/**
 * Class Swagger
 *
 * @package NYPL\Services
 */
class Swagger extends ServiceController
{
    /**
     * @return SwaggerGenerator
     */
    public function __invoke()
    {
        return SwaggerGenerator::generate(
          [__DIR__],
          $this->response
        );
    }
}
