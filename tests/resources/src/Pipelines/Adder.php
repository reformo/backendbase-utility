<?php
declare(strict_types=1);

namespace Selami\Resources\Pipelines;

use Selami\Stdlib\Pipeline\PipeInterface;

class Adder implements PipeInterface
{
    public function __construct() {}

    public function __invoke($payload)
    {
        $payload['result'] = $payload['firstNumber'] + $payload['secondNumber'];
        return $payload;
    }
}