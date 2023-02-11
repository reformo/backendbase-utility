<?php

declare(strict_types=1);

namespace Backendbase\Utility\Pipeline;

interface StageInterface
{
    public function __invoke($payload): void;
}
