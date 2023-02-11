<?php

declare(strict_types=1);

namespace Backendbase\Utility;

use function lcfirst;
use function mb_convert_case;
use function mb_strtolower;
use function preg_replace;
use function str_replace;
use function trim;

use const MB_CASE_TITLE;

class CaseConverter
{
    /**
     * Returns PascalCase string
     */
    public static function toPascalCase(string $source, string $separator = '_'): string
    {
        // If the string is snake case
        $modified              = str_replace($separator, ' ', $source);
        $lowercase             = mb_strtolower($modified);
        $uppercaseFirstLetters = mb_convert_case($lowercase, MB_CASE_TITLE);

        return str_replace(' ', '', $uppercaseFirstLetters);
    }

    /**
     * Returns camelCase string
     */
    public static function toCamelCase(string $source, string $separator = '_'): string
    {
        return lcfirst(self::toPascalCase($source, $separator));
    }

    /**
     * Returns snake_case string
     */
    public static function toSnakeCase(string $source, string $separator = '_'): string
    {
        // If the string is pascal/camel case
        $modified  = str_replace('  ', ' ', preg_replace('/[A-Z]+/', ' $0', $source));
        $lowercase = mb_strtolower(trim($modified));

        return str_replace(' ', $separator, $lowercase);
    }
}
