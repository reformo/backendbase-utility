<?php

declare(strict_types=1);

namespace Selami\Stdlib\Pipeline;

use Psr\Container\ContainerInterface;

class PipelineFactory
{
    public function __invoke(ContainerInterface $container): Pipeline
    {
        return new Pipeline($container);
    }
}
