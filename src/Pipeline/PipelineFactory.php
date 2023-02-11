<?php

declare(strict_types=1);

namespace Backendbase\Utility\Pipeline;

use Psr\Container\ContainerInterface;

class PipelineFactory
{
    public function __invoke(ContainerInterface $container): Pipeline
    {
        return Pipeline::withContainer($container);
    }
}
