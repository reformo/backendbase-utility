<?php
declare(strict_types=1);

namespace Selami\Stdlib;


class CaseConverter
{
    /**
     * Returns PascalCase string
     *
     * @param string $source
     * @return string
     *
     */
    public static function toPascalCase(string $source) : string
    {
        // If the string is snake case
        $modified = str_replace('_', ' ', $source);
        $lowercase = mb_strtolower($modified);
        $uppercaseFirstLetters = mb_convert_case($lowercase, MB_CASE_TITLE);
        return str_replace(' ', '', $uppercaseFirstLetters);
    }

    /**
     * Returns camelCase string
     *
     * @param string $source
     * @return string
     *
     */
    public static function toCamelCase(string $source) : string
    {
        return lcfirst(self::toPascalCase($source));
    }

    /**
     * Returns snake_case string
     *
     * @param string $source
     * @return string
     *
     */
    public static function toSnakeCase(string $source) : string
    {
        // If the string is pascal/camel case
        $modified = preg_replace('/(\w)([A-Z]+)/', ' $1', $source);
        $lowercase = mb_strtolower($modified);
        return str_replace(' ', '_', $lowercase);
    }

}