<?php
declare(strict_types=1);

namespace Selami\Stdlib;

class CaseConverter
{
    /**
     * Returns PascalCase string
     *
     * @param string $source
     * @param string $separator
     * @return string
     *
     */
    public static function toPascalCase(string $source, string $separator='_') : string
    {
        // If the string is snake case
        $modified = str_replace($separator, ' ', $source);
        $lowercase = mb_strtolower($modified);
        $uppercaseFirstLetters = mb_convert_case($lowercase, MB_CASE_TITLE);
        return str_replace(' ', '', $uppercaseFirstLetters);
    }

    /**
     * Returns camelCase string
     *
     * @param string $source
     * @param string $separator
     * @return string
     *
     */
    public static function toCamelCase(string $source, string $separator='_') : string
    {
        return lcfirst(self::toPascalCase($source, $separator));
    }

    /**
     * Returns snake_case string
     *
     * @param string $source
     * @param string $separator
     * @return string
     *
     */
    public static function toSnakeCase(string $source, string $separator='_') : string
    {
        // If the string is pascal/camel case
        $modified = str_replace('  ',' ',preg_replace('/[A-Z]+/', ' $0', $source));
        $lowercase = mb_strtolower(trim($modified));
        return str_replace(' ', $separator, $lowercase);
    }
}
