<?php
declare(strict_types=1);

namespace Backendbase\Resources\Pipelines;

use Backendbase\Utility\Pipeline\PipeInterface;

class Adder implements PipeInterface
{
    public function __construct() {}

    public function __invoke($payload)
    {
        $payload['result'] = $payload['firstNumber'] + $payload['secondNumber'];
        return $payload;
    }
}