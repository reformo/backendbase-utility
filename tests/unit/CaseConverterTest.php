<?php

declare(strict_types=1);

namespace SelamiTest;

use Codeception\Test\Unit;
use Selami\Stdlib\CaseConverter;
use UnitTester;

class CaseConverterTest extends Unit
{
    protected  $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
    }

    /**
     * @test
     * @dataProvider pascalCaseDataProvider
     * @var string $source
     * @var string $expected
     */
    public function shouldConvertStringToPascalCaseSuccessfully(string $source, string $expected): void
    {
        $result = CaseConverter::toPascalCase($source);
        $this->assertEquals($expected, $result);
    }

    public function pascalCaseDataProvider(): array
    {
        return [
            ['Kedibey ve Mirmir', 'KedibeyVeMirmir'],
            ['KEDIBEY VE MIRMIR', 'KedibeyVeMirmir'],
            ['kedibey ve mirmir', 'KedibeyVeMirmir'],
            ['kedibey_ve_mirmir', 'KedibeyVeMirmir'],
        ];
    }

    /**
     * @test
     * @dataProvider camelCaseDataProvider
     * @var string $source
     * @var string $expected
     */
    public function shouldConvertStringToCamelCaseSuccessfully(string $source, string $expected): void
    {
        $result = CaseConverter::toCamelCase($source);
        $this->assertEquals($expected, $result);
    }

    public function camelCaseDataProvider(): array
    {
        return [
            ['Kedibey ve Mirmir', 'kedibeyVeMirmir'],
            ['KEDIBEY VE MIRMIR', 'kedibeyVeMirmir'],
            ['kedibey ve mirmir', 'kedibeyVeMirmir'],
            ['kedibey_ve_mirmir', 'kedibeyVeMirmir'],
        ];
    }

    /**
     * @test
     * @dataProvider snakeCaseDataProvider
     * @var string $source
     * @var string $expected
     */
    public function shouldConvertStringToSnakeCaseSuccessfully(string $source, string $expected): void
    {
        $result = CaseConverter::toSnakeCase($source);
        $this->assertEquals($expected, $result);
    }

    public function snakeCaseDataProvider(): array
    {
        return [
            ['Kedibey ve Mirmir', 'kedibey_ve_mirmir'],
            ['kedibey ve mirmir', 'kedibey_ve_mirmir'],
            ['KedibeyVeMirmir', 'kedibey_ve_mirmir'],
        ];
    }
}
