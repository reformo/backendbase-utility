<?php

declare(strict_types=1);

namespace Backendbase\Utility\Pipeline;

use Psr\Container\ContainerInterface;
use Backendbase\Utility\Resolver;
use ReflectionClass;

class Pipeline implements PipelineInterface
{
    private array $stages = [];

    private function __construct(private ?\Psr\Container\ContainerInterface $container = null)
    {
    }

    public static function new(): self
    {
        return new self();
    }


    public static function withContainer(ContainerInterface $container): self
    {
        return new self($container);
    }

    public function pipe($stage): PipelineInterface
    {
        if (is_string($stage) && !$this->container->has($stage)) {
            throw new StageDoesNotExistException('DIC does not have requested stage service:' . $stage);
        }
        if (is_string($stage) && $this->container->has($stage)) {
            $stage = $this->getFromContainer($stage);
        }
        if (! $stage instanceof PipeInterface) {
            throw new InvalidStageException('Invalid Pipe staged');
        }
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


    public function getFromContainer(string $requestedName): PipeInterface
    {
        $handlerConstructorArguments = Resolver::getParameterHints($requestedName, '__construct');
        $arguments                   = [];
        foreach ($handlerConstructorArguments as $argumentName => $argumentType) {
            $arguments[] = $this->getArgument($argumentName, $argumentType);
        }
        $handlerClass = new ReflectionClass($requestedName);
        /**
         * @var PipeInterface
         */
        return $handlerClass->newInstanceArgs($arguments);
    }

    private function getArgument(string $argumentName, string $argumentType)
    {
        return $this->container->has($argumentType) ? $this->container->get($argumentType) :
            $this->container->get($argumentName);
    }
}
