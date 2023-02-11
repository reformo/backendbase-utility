<?php

declare(strict_types=1);

namespace UnitTest;

use Codeception\Test\Test;
use Codeception\Test\Unit;
use Backendbase;
use Backendbase\Resources\TypeHintedClass;
use Backendbase\Utility\Exception\ClassOrMethodCouldNotBeFound;

class ResolverTest extends Unit
{
    protected $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    /**
     * @test
     * @var string $className
     * @var string $methodName
     * @var array $expected
     * @dataProvider typeHintDataProvider
     */
    public function getTypeHintClassesSuccessfully(string $className, string $methodName, array $expected): void
    {
        $returned = Backendbase\Utility\Resolver::getParameterHints($className, $methodName);

        $this->assertEquals($expected, $returned);
    }

    public function typeHintDataProvider(): array
    {
        return [
            [TypeHintedClass::class, '__construct', ['test' => Test::class, 'unit' => Unit::class]],
            [TypeHintedClass::class, 'method', ['config' => 'array', 'unit' => Unit::class]],
        ];
    }

    /**
     * @test
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingClass(): void
    {
        $this->expectException(ClassOrMethodCouldNotBeFound::class);
        Backendbase\Utility\Resolver::getParameterHints('NonExistingClass', '__construct');
    }

    /**
     * @test
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingMethod(): void
    {
        $this->expectException(ClassOrMethodCouldNotBeFound::class);

        Backendbase\Utility\Resolver::getParameterHints(TypeHintedClass::class, 'nonExistingMethod');
    }
}
