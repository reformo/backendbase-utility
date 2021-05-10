<?php

declare(strict_types=1);

use Codeception\Test\Unit;
use Selami\Stdlib\BaseUrlExtractor;

class myBaseUrlExtractor extends Unit
{
    protected UnitTester $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    /**
     * @param array $serverData
     *
     * @test
     * @dataProvider serverRequestDataProvider
     */
    public function shouldFindBaseUrlSuccessfully(array $serverData, string $expected): void
    {
        $_SERVER = $serverData;
        $baseUrl = BaseUrlExtractor::getBaseUrl($serverData);
        $this->assertEquals($expected, $baseUrl);
    }

    public function serverRequestDataProvider()
    {
        return [
            [
                json_decode('{"REQUEST_URI":"\/test\/level-1\/level-2","SCRIPT_FILENAME":"\/mnt\/public\/site1\/test\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/test\/index.php","PHP_SELF":"\/test\/index.php\/level-1\/level-2","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080/test',
            ],
            [
                json_decode('{"REQUEST_URI":"\/tr\/basvuru\/bilgiler\/3","SCRIPT_FILENAME":"\/mnt\/public\/site1\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/index.php","PHP_SELF":"\/index.php\/tr\/basvuru\/bilgiler\/3","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080',
            ],
            [
                json_decode('{"REQUEST_URI":"\/","SCRIPT_FILENAME":"\/mnt\/public\/site1\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/index.php","PHP_SELF":"\/index.php","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080',
            ],
            [
                json_decode('{"REQUEST_URI":"\/tr","SCRIPT_FILENAME":"\/mnt\/public\/site1\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/index.php","PHP_SELF":"\/index.php\/tr","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080',
            ],
            [
                json_decode('{"REQUEST_URI":"\/index.php","SCRIPT_FILENAME":"\/mnt\/public\/site1\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/index.php","PHP_SELF":"\/index.php","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080',
            ],
            [
                json_decode('{"REQUEST_URI":"\/test\/index.php","SCRIPT_FILENAME":"\/mnt\/public\/site1\/test\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/test\/index.php","PHP_SELF":"\/test\/index.php","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080/test',
            ],
        ];
    }
}
