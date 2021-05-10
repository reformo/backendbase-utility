<?php

declare(strict_types=1);

namespace Selami\Stdlib;

use ReflectionClass;

use function is_object;

/**
 * This class provides methods to build a equals method for any class. Intended to be use to compare Value Objects.
 */

final class EqualsBuilder
{
    private $isEquals = true;

    public static function create(): EqualsBuilder
    {
        return new static();
    }

    public function append($leftHandedValue, $rightHandedValue)
    {
        if ($this->isEquals === false) {
            return $this;
        }

        if ($this->checkIfValuesAreAnObjectAndEqual($leftHandedValue, $rightHandedValue)) {
            return $this;
        }

        if ($this->checkForSameType($leftHandedValue, $rightHandedValue)) {
            return $this;
        }

        return $this;
    }

    private function checkForSameType($leftHandedValue, $rightHandedValue): bool
    {
        if ($leftHandedValue !== $rightHandedValue) {
            $this->isEquals = false;

            return false;
        }

        return true;
    }

    private function checkIfValuesAreAnObjectAndEqual($leftHandedValue, $rightHandedValue): bool
    {
        if (! $this->checkIfValuesAreAnObject($leftHandedValue, $rightHandedValue)) {
            return false;
        }

        if ($leftHandedValue::class !== $rightHandedValue::class) {
            $this->isEquals = false;

            return false;
        }

        if (! $this->compareObjectProperties($leftHandedValue, $rightHandedValue)) {
            $this->isEquals = false;

            return false;
        }

        return true;
    }

    private function checkIfValuesAreAnObject($leftHandedValue, $rightHandedValue): bool
    {
        return is_object($leftHandedValue) && is_object($rightHandedValue);
    }

    private function compareObjectProperties($leftHandedObject, $rightHandedObject): bool
    {
        $reflectionOfLeftHandedObject  = new ReflectionClass($leftHandedObject);
        $propertiesOfLeftHandedObject  = $this->getPropertiesAsAnArray($leftHandedObject, $reflectionOfLeftHandedObject);
        $reflectionOfRightHandedObject = new ReflectionClass($rightHandedObject);
        $propertiesOfRightHandedObject = $this->getPropertiesAsAnArray(
            $rightHandedObject,
            $reflectionOfRightHandedObject
        );

        return $this->checkValuesRecursively($propertiesOfRightHandedObject, $propertiesOfLeftHandedObject);
    }

    private function checkValuesRecursively(
        array $propertiesOfLeftHandedObject,
        array $propertiesOfRightHandedObject
    ): bool {
        $innerEqualsBuilder = self::create();
        foreach ($propertiesOfLeftHandedObject as $propertyName => $propertyValue) {
            $innerEqualsBuilder = $innerEqualsBuilder->append(
                $propertyValue,
                $propertiesOfRightHandedObject[$propertyName]
            );
        }

        return $innerEqualsBuilder->isEquals();
    }

    private function getPropertiesAsAnArray($sourceObject, $object): array
    {
        $propertyList = [];
        foreach ($object->getProperties() as $property) {
            if ($property->isProtected() || $property->isPrivate()) {
                $property->setAccessible(true);
            }

            $propertyList[$property->name] = $property->getValue($sourceObject);
        }

        return $propertyList;
    }

    public function isEquals(): bool
    {
        return $this->isEquals;
    }
}
