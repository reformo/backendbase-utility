<?php

use Selami\Stdlib\BaseUrlExtractor;

class myBaseUrlExtractor extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @test
     * @param array $serverData
     * @param string $expected
     * @dataProvider serverRequestDataProvider
     */
    public function shouldFindBaseUrlSuccessfully(array $serverData, string $expected)
    {
        $_SERVER = $serverData;
        $baseurl = BaseUrlExtractor::getBaseUrl($serverData);
        $this->assertEquals($expected,$baseurl);
    }


    public function serverRequestDataProvider()
    {
        return [
            [
                json_decode('{"REQUEST_URI":"\/test\/level-1\/level-2","SCRIPT_FILENAME":"\/mnt\/public\/site1\/test\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/test\/index.php","PHP_SELF":"\/test\/index.php\/level-1\/level-2","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080/test'
            ],
            [
                json_decode('{"REQUEST_URI":"\/tr\/basvuru\/bilgiler\/3","SCRIPT_FILENAME":"\/mnt\/public\/site1\/index.php","HTTP_HOST":"127.0.0.1:8080","SCRIPT_NAME":"\/index.php","PHP_SELF":"\/index.php\/tr\/basvuru\/bilgiler\/3","ORIG_SCRIPT_NAME":""}', true),
                'http://127.0.0.1:8080'
            ]
        ];
    }
}