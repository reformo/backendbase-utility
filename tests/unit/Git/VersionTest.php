<?php

namespace SelamiTest;

use Codeception\Test\Unit;
use Selami\Stdlib\Git\Version;
use SebastianBergmann\Version as SBVersion;
class VersionTest extends Unit
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
     *
     */
    public function shouldReturnShortGitVersion() : void
    {
        $path = dirname(__DIR__, 3);
        $version = new SBVersion(
            '1.0.0', $path
        );
        $haystack = $version->getVersion();
        $result = Version::short();
        $this->assertContains($result, $haystack);
    }

}
