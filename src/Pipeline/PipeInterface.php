<?php
declare(strict_types=1);

namespace Backendbase\Utility\Pipeline;

interface PipeInterface
{
    public function __invoke($payload);
}