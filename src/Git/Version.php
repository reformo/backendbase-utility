<?php

declare(strict_types=1);

namespace Backendbase\Utility\Git;

use function exec;

final class Version
{
    public static function short()
    {
        exec('git describe --always', $versionMiniHash);

        return $versionMiniHash[0];
    }
}
