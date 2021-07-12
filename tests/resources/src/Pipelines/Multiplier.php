<?php
declare(strict_types=1);

namespace Selami\Resources\Pipelines;

use Selami\Stdlib\Pipeline\PipeInterface;

class Multiplier implements PipeInterface
{
    private int $multiplyBy;

    public function __construct(int $multiplyBy)
    {
        $this->multiplyBy = $multiplyBy;
    }
    public function __invoke($payload)
    {
        $payload['result'] *= $this->multiplyBy;
        return $payload;
    }
}