<?php

declare(strict_types=1);

namespace Selami\Stdlib\Pipeline;

use Psr\Container\ContainerInterface;

interface PipelineInterface
{
    public function pipe($stage): PipelineInterface;

    public static function withContainer(ContainerInterface $container): self;

    public function process($payload);
}
