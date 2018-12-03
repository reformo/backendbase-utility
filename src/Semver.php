<?php
declare(strict_types=1);

namespace Selami\Stdlib;

use Selami\Stdlib\Exception\InvalidSemverPatternException;

/**
 * Class Semver
 * Semantic Versioning 2.0.0 compatible version handling
 * @package Selami\Stdlib
 */
final class Semver
{
    private static $semverPattern = '/(\d+).(\d+).(\d+)(|[-.+](?:dev|alpha|beta|rc|stable)(.*?))$/i';
    private $major;
    private $minor;
    private $patch;
    private $preRelease;

    private function __construct(int $major, int $minor, int $patch, ?string $preRelase = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->preRelease = $preRelase;
    }

    public static function createFromString(string $version) : self
    {
        $preRelease = null;
        if (!preg_match(self::$semverPattern, $version, $match)) {
            throw new InvalidSemverPatternException(
                sprintf('%s is not valid semver compatible version name', $version)
            );
        }
        [,$major, $minor, $patch, $preRelease] = $match;
        if ($preRelease === '') {
            $preRelease = null;
        }
        return new self((int) $major, (int) $minor, (int) $patch, $preRelease);
    }

    public function getCurrent() : string
    {
        return $this->major . '.'
            . $this->minor . '.'
            . $this->patch
            . $this->preRelease;
    }

    public function getNextPatchRelease() : string
    {
        return $this->major . '.' . $this->minor . '.' . (++$this->patch);
    }

    public function getNextMinorRelease() : string
    {
        return $this->major . '.' . (++$this->minor) . '.' . $this->patch;
    }

    public function getNextMajorRelease() : string
    {
        if ($this->preRelease === null) {
            $this->major++;
        }
        return $this->major . '.'
            . $this->minor . '.'
            . $this->patch;
    }
}
