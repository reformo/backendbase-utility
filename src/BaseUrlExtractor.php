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
        $origScriptName = $httpServerData['ORIG_SCRIPT_NAME'] ?? '';
        $baseUrl = self::getRelativeBaseUrl($scriptName, $phpSelf, $filename, $origScriptName);
        return trim(self::determineFullBaseUrl($baseUrl, $protocol, $host, $uriPath), '/');
    }

    public static function determineFullBaseUrl($baseUrl, $protocol, $host, $uriPath): string
    {
        // If the baseUrl is empty, then ply return it
        if (empty($baseUrl)) {
            return $protocol . '://' . $host.'';
        }
        // Full base URL matches.
        if (0 === strpos($uriPath, $baseUrl)) {
            return $protocol . '://' . $host.$baseUrl;
        }
        // Directory portion of base path matches.
        $baseDir = str_replace('\\', '/', dirname($baseUrl));
        if (0 === strpos($uriPath, $baseDir)) {
            return $protocol . '://' . $host.$baseDir;
        }
        $basename = basename($baseUrl);
        // No match whatsoever
        if (empty($basename) || false === strpos($uriPath, $basename)) {
            return $protocol . '://' . $host;
        }
        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of the base path. $pos !== 0 makes sure it is not matching a
        // value from PATH_INFO or QUERY_STRING.
        if ((false !== ($pos = strpos($uriPath, $baseUrl)) && $pos !== 0) && strlen($uriPath) >= strlen($baseUrl)
        ) {
            $baseUrl = substr($uriPath, 0, $pos + strlen($baseUrl));
        }
        return trim($protocol . '://' . $host .$baseUrl, '/');
    }


    public static function getProtocol(array $httpServerData) : string
    {
        return isset($httpServerData['HTTPS']) && $httpServerData['HTTPS'] !== 'Off' ? 'https': 'http';
    }

    public static function getHost($httpServerData) : string
    {
        return $httpServerData['HTTP_HOST'];
    }

    public static function getRelativeBaseUrl($scriptName, $phpSelf, $filename, $origScriptName) : string
    {
        if ($scriptName !== null && basename($scriptName) === $filename) {
            return $scriptName;
        }
        if ($phpSelf !== null && basename($phpSelf) === $filename) {
            return $phpSelf;
        }
        if ($origScriptName !== null && basename($origScriptName) === $filename) {
            // 1and1 shared hosting compatibility.
            return $origScriptName;
        }
        // Backtrack up the SCRIPT_FILENAME to find the portion
        // matching PHP_SELF.
        $baseUrl  = '/';
        $basename = basename($filename);
        if ($basename) {
            $path     = ($phpSelf ? trim($phpSelf, '/') : '');
            $basePos  = strpos($path, $basename) ?: 0;
            $baseUrl .= substr($path, 0, $basePos) . $basename;
        }
        return $baseUrl;
    }
}
