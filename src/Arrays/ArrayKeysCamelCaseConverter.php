<?php

declare(strict_types=1);

namespace Selami\Stdlib\Arrays;

use Selami\Stdlib\CaseConverter;

use function is_array;
use function is_string;
use function strpos;

class ArrayKeysCamelCaseConverter
{
    public static function convertArrayKeys(array $arrayItems): array
    {
        $newArray = [];
        foreach ($arrayItems as $key => $value) {
            if (is_array($value)) {
                $value = self::convertArrayKeys($value);
            }

            if (is_string($key) && strpos($key, '_') !== false) {
                $key = CaseConverter::toCamelCase($key);
            }

            $newArray[$key] = $value;
        }

        return $newArray;
    }
}
