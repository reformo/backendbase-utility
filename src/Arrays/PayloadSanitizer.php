<?php

declare(strict_types=1);

namespace Backendbase\Utility\Arrays;

use function array_key_exists;
use function filter_var;
use function is_array;
use function is_int;
use function is_string;
use function password_hash;
use function htmlspecialchars;

use const FILTER_FLAG_NO_ENCODE_QUOTES;
use const FILTER_SANITIZE_EMAIL;
use const PASSWORD_ARGON2ID;

class PayloadSanitizer
{
    public static function sanitize(?array $payload, ?array $allowHtml = [], ?string $keyValue = '$'): array
    {
        if ($payload === null) {
            return [];
        }

        $sanitizedPayload = [];
        foreach ($payload as $key => $value) {
            if (is_string($key)) {
                $key = htmlspecialchars($key,ENT_COMPAT | ENT_HTML5);
            }

            $currentKeyValue = $keyValue . '.' . $key;
            if (array_key_exists($currentKeyValue, $allowHtml)) {
                $sanitizedPayload[$key] = TagAndAttributeRemover::cleanHtml(
                    $value,
                    $allowHtml[$currentKeyValue]['allowedTags'],
                    $allowHtml[$currentKeyValue]['urlPrefixes'] ?? 'https'
                );
                continue;
            }

            if (is_int($value)) {
                $sanitizedPayload[$key] =  $value;
                continue;
            }

            if ($key === 'email') {
                $sanitizedPayload[$key] =  filter_var($value, FILTER_SANITIZE_EMAIL);
                continue;
            }

            if ($key === 'password') {
                $sanitizedPayload['passwordHash']     =  password_hash($payload['password'], PASSWORD_ARGON2ID);
                $sanitizedPayload['passwordHashAlgo'] = PASSWORD_ARGON2ID;
                continue;
            }

            if (is_string($value)) {
                $sanitizedPayload[$key] = htmlspecialchars($value, ENT_COMPAT | ENT_HTML5);
                continue;
            }

            if (is_array($value)) {
                $sanitizedPayload[$key] =  self::sanitize($value, $allowHtml, $keyValue . '.' . $key);
                continue;
            }

            $sanitizedPayload[$key] = $value;
        }

        return $sanitizedPayload;
    }
}
