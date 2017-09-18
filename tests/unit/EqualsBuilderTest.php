<?php

namespace SelamiTest;

use Codeception\Test\Unit;
use Selami\Stdlib\EqualsBuilder;

class EqualsBuilderTest extends Unit
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
    public function shouldReturnTrueForEqualValues() : void
    {
        $result = EqualsBuilder::create()
            ->append(1,1)
            ->append('a string', 'a string')
            ->append(new TestValueObject(2, 'Kedibey'), new TestValueObject(2, 'Kedibey'))
            ->isEquals();
        $this->assertTrue($result);
    }

    public function shouldReturnFalseForEqualValues() : void
    {
        $result = EqualsBuilder::create()
            ->append(1,1)
            ->append('a string', 'a string')
            ->append(new TestValueObject(2, 'Kedibey'), new TestValueObject(2, 'Mırmır'))
            ->isEquals();
        $this->assertFalse($result);
    }

}

class TestValueObject{
    private $var1;
    private $var2;

    public function __construct(int $var1, string $var2)
    {
        $this->var1 = $var1;
        $this->var2 = $var2;
    }
}
