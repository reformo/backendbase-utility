<?php
namespace SelamiTest;

use Selami\Stdlib\Semver;

class SemverTest extends \Codeception\Test\Unit
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
     */
    public function shouldReturnCurrentVersionSuccessfully() : void
    {
        $version = '2.0.6';
        $semver = Semver::createFromString($version);
        $this->assertEquals($version, $semver->getCurrent());
    }

    /**
     * @test
     */
    public function shouldReturnNextPatchVersionSuccessfully() : void
    {
        $version = '2.0.6';
        $semver = Semver::createFromString($version);
        $this->assertEquals('2.0.7', $semver->getNextPatchRelease());
    }

    /**
     * @test
     */
    public function shouldReturnNextMinorVersionSuccessfully() : void
    {
        $version = '2.0.6';
        $semver = Semver::createFromString($version);
        $this->assertEquals('2.1.6', $semver->getNextMinorRelease());
    }

    /**
     * @test
     */
    public function shouldReturnNextMajorVersionSuccessfully() : void
    {
        $version = '2.0.6';
        $semver = Semver::createFromString($version);
        $this->assertEquals('3.0.6', $semver->getNextMajorRelease());
    }

    /**
     * @test
     */
    public function shouldReturnNextMajorVersionWithPreReleaseSuccessfully() : void
    {
        $version = '2.0.6-alpha.1';
        $semver = Semver::createFromString($version);
        $this->assertEquals('2.0.6-alpha.1', $semver->getCurrent());
        $this->assertEquals('2.0.6', $semver->getNextMajorRelease());
    }

    /**
     * @test
     * @expectedException \Selami\Stdlib\Exception\InvalidSemverPatternException
     */
    public function shouldFailForInvalidVersion() : void
    {
        $version = '2.0.a';
        $semver = Semver::createFromString($version);
        $this->assertEquals('3.0.6', $semver->getNextMajorRelease());
    }
}
