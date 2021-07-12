<?php
declare(strict_types=1);

namespace Selami\Stdlib\Pipeline;

interface PipeInterface
{
    public function __invoke($payload);
}