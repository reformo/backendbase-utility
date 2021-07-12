<?php

declare(strict_types=1);

namespace UnitTest\Arrays;

use PHPUnit\Framework\TestCase;
use Selami\Stdlib\Arrays\ArrayExtractor;

final class ArrayExtractorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldExtractValuesSuccessfully(): void
    {
        $data = [
            'name' => 'Kedibey',
            'familyName' => 'Kedigil',
            'age' => 12,
            'birthday' => '2008-06-01',
            'gender' => 'male',
            'address' => [
                'city' => 'İstanbul',
                'district' => 'Kadıköy',
                'street' => 'İnönü Cad',
                'coordinates' => [
                    'lat' => 0.0,
                    'lon' => 0.0,
                ],
            ],
        ];

        $extractModel = ['name', 'familyName', 'gender', 'address' => ['city', 'coordinates' => ['lat']]];
        $actual       = ArrayExtractor::extract($extractModel, $data);
        $this->assertArrayHasKey('address', $actual);
        $this->assertArrayHasKey('coordinates', $actual['address']);
        $this->assertArrayHasKey('lat', $actual['address']['coordinates']);
        $this->assertArrayNotHasKey('lon', $actual['address']['coordinates']);
        $this->assertSame($data['address']['city'], $actual['address']['city']);
    }
}
