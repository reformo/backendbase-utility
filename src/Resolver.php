<?php

declare(strict_types=1);

namespace Selami\Stdlib;

use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use Selami\Stdlib\Exception\ClassOrMethodCouldNotBeFound;

use function assert;
use function class_exists;
use function is_array;
use function method_exists;
use function sprintf;

class Resolver
{
    public const ARRAY    = 'array';
    public const CALLABLE = 'callable';
    public const BOOLEAN  = 'boolean';
    public const FLOAT    = 'float';
    public const INTEGER  = 'int';
    public const STRING   = 'string';
    public const ITERABLE = 'iterable';

    public static function getParameterHints(string $className, string $methodName): array
    {
        self::checkClassName($className);
        self::checkMethodName($className, $methodName);
        try {
            $method = new ReflectionMethod($className, $methodName);
        } catch (ReflectionException) {
            throw new ClassOrMethodCouldNotBeFound(
                sprintf('%s::%s coulnd not be found.', $className, $methodName)
            );
        }

        $parameters = $method->getParameters();
        assert(is_array($parameters));
        $parameterHints = [];
        foreach ($parameters as $param) {
            $parameter                          = self::getParamType($param);
            $parameterHints[$parameter['name']] = $parameter['type'];
        }

        return $parameterHints;
    }

    private static function checkClassName(string $className): void
    {
        if (! class_exists($className)) {
            throw new ClassOrMethodCouldNotBeFound(
                sprintf('%s class does not exist!', $className)
            );
        }
    }

    private static function checkMethodName(string $className, string $methodName): void
    {
        if (! method_exists($className, $methodName)) {
            throw new ClassOrMethodCouldNotBeFound(
                sprintf('%s does not have method named %s!', $className, $methodName)
            );
        }
    }

    private static function getParamType(ReflectionParameter $parameter): array
    {
        $type = $parameter->getType();
        if ($type->isBuiltin()) {
            return ['name' => $parameter->name, 'type' => $type->getName()];
        }

        try {
            return ['name' => $parameter->name, 'type' => $type->getName()];
        } catch (ReflectionException $e) {
            throw new ClassOrMethodCouldNotBeFound($e->getMessage());
        }
    }
}
