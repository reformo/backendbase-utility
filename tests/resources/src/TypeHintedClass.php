<?php
declare(strict_types=1);

namespace Selami\Resources;

use Codeception\Test\Test;
use Codeception\Test\Unit;
use Codeception\Test\NonExistingClass;

class TypeHintedClass
{
    public function __construct(Test $test, Unit $unit)
    {
    }

    public function method(array $config, Unit $unit) : void
    {
        // does nothing
    }
    public function method2(array $config, NonExistingClass $unit) : void
    {
        // does nothing
    }
}
