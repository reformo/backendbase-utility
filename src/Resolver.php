<?php
declare(strict_types=1);

namespace Selami\Stdlib;

use ReflectionMethod;
use ReflectionParameter;
use Selami\Stdlib\Exception\InvalidArgumentException;
use ReflectionException;

class Resolver
{
    /**
     * @param string $className
     * @param string $methodName
     * @return array
     * @throws InvalidArgumentException
     */

    public static function getParameterHints(string $className, string $methodName) : array
    {
        if (! class_exists($className)) {
            throw new InvalidArgumentException(
                sprintf('%s class does not exist!', $className)
            );
        }
        if (! method_exists($className, $methodName)) {
            throw new InvalidArgumentException(
                sprintf('%s does not have method named %s!', $className, $methodName)
            );
        }
        $method = new ReflectionMethod($className, $methodName);
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
