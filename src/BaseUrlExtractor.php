<?php
declare(strict_types=1);

namespace Selami\Stdlib;

class BaseUrlExtractor
{
    public static function getBaseUrl(array $httpServerData) : string
    {
        $protocol = self::getProtocol($httpServerData);
        $host = self::getHost($httpServerData);
        $uriPath = $httpServerData['REQUEST_URI'] ?? '';
        $filename = $httpServerData['SCRIPT_FILENAME'] ?? '';
        $scriptName = $httpServerData['SCRIPT_NAME'];
        $phpSelf = $httpServerData['PHP_SELF'];
        $baseUrl = self::getRelativeBaseUrl($scriptName, $phpSelf, $filename);
        return trim($protocol . '://' . $host . $baseUrl, '/');
    }



    public static function getProtocol(array $httpServerData) : string
    {
        return isset($httpServerData['HTTPS']) && $httpServerData['HTTPS'] !== 'Off' ? 'https': 'http';
    }

    public static function getHost($httpServerData) : string
    {
        return $httpServerData['HTTP_HOST'];
    }

    public static function getRelativeBaseUrl($scriptName, $phpSelf, $filename) : string
    {

        // Backtrack up the SCRIPT_FILENAME to find the portion
        // matching PHP_SELF.
        $baseUrl  = '/';
        $basename = basename($filename);
        if ($basename) {
            $path     = ($phpSelf ? trim($phpSelf, '/') : '');
            $basePos  = strpos($path, $basename) ?: 0;
            $baseUrl .= substr($path, 0, $basePos) ;
        }
        return $baseUrl;
    }
}
