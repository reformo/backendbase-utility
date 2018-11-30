<?php
declare(strict_types=1);

namespace Selami\Stdlib;

use ReflectionMethod;
use ReflectionParameter;
use Selami\Stdlib\Exception\ClassOrMethodCouldNotBeFound;
use Selami\Stdlib\Exception\InvalidArgumentException;
use ReflectionException;

class Resolver
{
    const ARRAY = 'array';
    const CALLABLE = 'callable';
    const BOOLEAN = 'boolean';
    const FLOAT = 'float';
    const INTEGER = 'int';
    const STRING = 'string';
    const ITERABLE = 'iterable';

    /**
     * @param string $className
     * @param string $methodName
     * @return array
     * @throws InvalidArgumentException
     * @throws ClassOrMethodCouldNotBeFound
     */

    public static function getParameterHints(string $className, string $methodName) : array
    {

        self::checkClassName($className);
        self::checkMethodName($className, $methodName);
        try {
            $method = new ReflectionMethod($className, $methodName);
        } catch (ReflectionException $e) {
            throw new ClassOrMethodCouldNotBeFound(
                sprintf('%s::%s coulnd not be found.', $className, $methodName)
            );
        }
        /**
         * @var array $parameters
         */
        $parameters = $method->getParameters();
        $parameterHints = [];
        foreach ($parameters as $param) {
            $parameter = self::getParamType($param);
            $parameterHints[$parameter['name']] = $parameter['type'];
        }
        return $parameterHints;
    }

    private static function checkClassName(string $className) : void
    {
        if (! class_exists($className)) {
            throw new InvalidArgumentException(
                sprintf('%s class does not exist!', $className)
            );
        }
    }

    private static function checkMethodName(string $className, string $methodName) : void
    {
        if (! method_exists($className, $methodName)) {
            throw new InvalidArgumentException(
                sprintf('%s does not have method named %s!', $className, $methodName)
            );
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @return array
     * @throws InvalidArgumentException
     */
    private static function getParamType(ReflectionParameter $parameter) :array
    {
        $type = $parameter->getType();
        if ($type->isBuiltin()) {
            return ['name' => $parameter->name, 'type' => (string) $type];
        }
        try {
            return ['name' => $parameter->name, 'type' =>$parameter->getClass()->name];
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}
