<?php

declare(strict_types=1);

namespace Selami\Stdlib\Pipeline;

use Psr\Container\ContainerInterface;

class Pipeline implements PipelineInterface
{
    private ContainerInterface $container;
    private array $stages = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function fromContainer(string $className): callable
    {
        if (! $this->container->has($className)) {
            throw new StageDoesNotExistException('DIC does not have requested stage service:' . $className);
        }

        return $this->container->get($className);
    }

    public function pipe(callable $stage): PipelineInterface
    {
        $this->stages[] = $stage;

         return $this;
    }

    public function process($payload)
    {
        foreach ($this->stages as $stage) {
            $payload = $stage($payload);
        }

        return $payload;
    }
}
