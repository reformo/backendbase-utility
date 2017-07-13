<?php

use Selami\Resources\TypeHintedClass;
use Codeception\Test\Test;
use Codeception\Test\Unit;

class ParameterResolverTest extends Unit
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
     * @var string $className
     * @var string $methodName
     * @var array $expected
     * @dataProvider typeHintDataProvider
     */
    public function getTypeHintClassesSuccessfully(string $className, string $methodName, array $expected) : void
    {
        $returned = Selami\Stdlib\Resolver::getParameterHints($className, $methodName);

        $this->assertEquals($expected, $returned);
    }

    public function typeHintDataProvider() : array
    {
        return [
            [TypeHintedClass::class, '__construct', ['test' => Test::class, 'unit' => Unit::class]],
            [TypeHintedClass::class, 'method', ['config'=>'array', 'unit' => Unit::class]]
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingClass() : void
    {
        Selami\Stdlib\Resolver::getParameterHints('NonExistingClass', '__construct');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingMethod() : void
    {
        Selami\Stdlib\Resolver::getParameterHints(TypeHintedClass::class, 'nonExistingMethod');
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function getTypeHintClassesShouldThrowExceptionForNonExistingClassHint() : void
    {
        Selami\Stdlib\Resolver::getParameterHints(TypeHintedClass::class, 'method2');
    }
}