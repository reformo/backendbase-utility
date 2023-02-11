<?php

declare(strict_types=1);

namespace Backendbase\Utility\Arrays;

use Backendbase\Utility\CaseConverter;

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

            if (is_string($key) && str_contains($key, '_')) {
                $key = CaseConverter::toCamelCase($key);
            }

            $newArray[$key] = $value;
        }

        return $newArray;
    }
}
