<?php
namespace SelamiTest;

use Selami\Stdlib\Exception\InvalidSemverPatternException;
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
        $this->assertEquals('2.1.0', $semver->getNextMinorRelease());
    }

    /**
     * @test
     */
    public function shouldReturnNextMajorVersionSuccessfully() : void
    {
        $version = '2.0.6';
        $semver = Semver::createFromString($version);
        $this->assertEquals('3.0.0', $semver->getNextMajorRelease());
    }

    /**
     * @test
     */
    public function shouldReturnNextVersionsWithPreReleaseSuccessfully() : void
    {
        $version = '2.0.6-alpha.1';
        $semver = Semver::createFromString($version);
        $this->assertEquals('2.0.6-alpha.1', $semver->getCurrent());
        $this->assertEquals('2.0.6', $semver->getNextMajorRelease());
        $this->assertEquals('2.0.6', $semver->getNextMinorRelease());
        $this->assertEquals('2.0.6', $semver->getNextPatchRelease());
    }

    /**
     * @test
     */
    public function shouldFailForInvalidVersion() : void
    {
        $this->expectException(InvalidSemverPatternException::class);
        $version = '2.0.a';
        $semver = Semver::createFromString($version);
        $this->assertEquals('3.0.6', $semver->getNextMajorRelease());
    }
}
