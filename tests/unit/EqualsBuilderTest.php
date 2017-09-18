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
            ->append(1, 1)
            ->append('a string', 'a string')
            ->append(new TestValueObject(2, 'Kedibey'), new TestValueObject(2, 'Kedibey'))
            ->isEquals();
        $this->assertTrue($result);
    }
    /**
     * @test
     * @dataProvider returnFalseDataProvider
     * @param array $firstValues
     * @param array $secondValues
     * @param array $thirdValues
     *
     */
    public function shouldReturnFalseForEqualValues(array $firstValues, array $secondValues, array $thirdValues) : void
    {
        $result = EqualsBuilder::create()
            ->append($firstValues[0], $firstValues[1])
            ->append($secondValues[0], $secondValues[1])
            ->append($thirdValues[0], $thirdValues[1])
            ->isEquals();
        $this->assertFalse($result);
    }

    public function returnFalseDataProvider()
    {
        return [
            [[1, 2], ['str', 'str'], [0.1, 0,1]],
            [[2, 2], ['str', 'string'], [0.1, 0,1]],
            [[2, 2], ['str', 'str'], [ new TestValueObject(2, 'Kedibey'), new TestValueObject(2, 'Mırmır')]],
            [[2, 2], ['str', 'str'], [ new TestValueObject(2, 'Kedibey'), new \stdClass()]],
        ];
    }
}

class TestValueObject
{
    private $var1;
    private $var2;

    public function __construct(int $var1, string $var2)
    {
        $this->var1 = $var1;
        $this->var2 = $var2;
    }
}
