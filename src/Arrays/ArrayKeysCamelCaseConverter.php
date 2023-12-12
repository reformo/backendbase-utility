<?php

declare(strict_types=1);

namespace Backendbase\Utility\Arrays;

use Backendbase\Utility\CaseConverter;
use stdClass;

use function is_array;
use function is_string;

class ArrayKeysCamelCaseConverter
{
    public static function convertArrayKeys(array $arrayItems): array
    {
        $newArray = [];
        foreach ($arrayItems as $key => $value) {
            if (is_array($value)) {
                $value = self::convertArrayKeys($value);
            }

            if (is_string($key) && str_contains($key, '_')) {
                $key = CaseConverter::toCamelCase($key);
            }

            $newArray[$key] = $value;
        }

        return $newArray;
    }

    public static function convertKeysAndPropertyNames(stdClass $object): stdClass
    {
        $arrayItems = get_object_vars($object);

        $newObject = new stdClass();
        foreach ($arrayItems as $key => $value) {
            if (is_array($value)) {
                $value = self::convertArrayKeys($value);
            }
            else if ($value instanceof stdClass) {
                $value = self::convertKeysAndPropertyNames($value);
            }


            if (is_string($key) && str_contains($key, '_')) {
                $key = CaseConverter::toCamelCase($key);
            }

            $newObject->{$key} = $value;
        }

        return $newObject;
    }
}
