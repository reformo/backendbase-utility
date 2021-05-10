<?php

declare(strict_types=1);

namespace Selami\Stdlib\Pipeline;

interface PipelineInterface
{
    public function pipe(callable $stage): PipelineInterface;

    public function fromContainer(string $className): callable;

    public function process($payload): void;
}
