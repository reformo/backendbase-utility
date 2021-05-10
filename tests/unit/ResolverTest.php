<?php

declare(strict_types=1);

namespace SelamiTest;

use Codeception\Test\Test;
use Codeception\Test\Unit;
use Selami;
use Selami\Resources\TypeHintedClass;
use Selami\Stdlib\Exception\ClassOrMethodCouldNotBeFound;
use UnitTester;

class ResolverTest extends Unit
{
    protected UnitTester $tester;

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
        $returned = Selami\Stdlib\Resolver::getParameterHints($className, $methodName);

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
        Selami\Stdlib\Resolver::getParameterHints('NonExistingClass', '__construct');
    }

    /**
     * @test
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingMethod(): void
    {
        $this->expectException(ClassOrMethodCouldNotBeFound::class);

        Selami\Stdlib\Resolver::getParameterHints(TypeHintedClass::class, 'nonExistingMethod');
    }

    /**
     * @test
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingClassHint(): void
    {
        $this->expectException(ClassOrMethodCouldNotBeFound::class);
        $result = Selami\Stdlib\Resolver::getParameterHints(TypeHintedClass::class, 'method2');
    }
}
