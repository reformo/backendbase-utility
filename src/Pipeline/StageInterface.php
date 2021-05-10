<?php

declare(strict_types=1);

namespace Selami\Stdlib\Pipeline;

interface StageInterface
{
    public function __invoke($payload): void;
}
