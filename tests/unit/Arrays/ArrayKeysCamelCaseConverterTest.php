<?php

declare(strict_types=1);

namespace UnitTest\Arrays;

use Backendbase\Utility\Arrays\ArrayKeysCamelCaseConverter;
use PHPUnit\Framework\TestCase;
use Backendbase\Utility\Arrays\ArrayExtractor;

final class ArrayKeysCamelCaseConverterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConvertArrayKeyCasesSuccessfully(): void
    {
        $data = [
            'name' => 'Kedibey',
            'family_name' => 'Kedigil',
            'age' => 12,
            'birthday' => '2008-06-01',
            'gender' => 'male',
            'address' => [
                'city_and_district' => 'İstanbul/Kadıköy',
                'street' => 'İnönü Cad',
                'coordinates' => [
                    'lat' => 0.0,
                    'lon' => 0.0,
                ],
            ],
        ];


        $actual       = ArrayKeysCamelCaseConverter::convertArrayKeys($data);
        $this->assertArrayHasKey('familyName', $actual);
        $this->assertArrayHasKey('cityAndDistrict', $actual['address']);
        $this->assertSame($data['address']['city_and_district'], $actual['address']['cityAndDistrict']);
    }

    /**
     * @test
     */
    public function shouldConvertStdClassPropertyCasesSuccessfully(): void
    {
        $data = new \stdClass();

        $data->family_name = 'Kedigil';

        $data->address = new \stdClass();
        $data->address->city_and_district = 'İstanbul/Kadıköy';

        $data->additional_info = new \stdClass();
        $data->additional_info->social_media = [
            'twitter' => 'none',
            'facebook' => 'none',
            'linkedin' => 'none',
            'cats_social' => 'kedibey'
        ];

        $data->metadata = [
            'sys_log' => (object) ['date' => date('Y-m-d H:i:s'), 'type' => 'log'],
        ];

        $actual       = ArrayKeysCamelCaseConverter::convertKeysAndPropertyNames($data);
        $this->assertObjectHasProperty('familyName', $actual);
        $this->assertObjectHasProperty('additionalInfo', $actual);
        $this->assertObjectHasProperty('metadata', $actual);
        $this->assertObjectHasProperty('cityAndDistrict', $actual->address);
        $this->assertObjectHasProperty('socialMedia', $actual->additionalInfo);
        $this->assertArrayHasKey('sysLog', $actual->metadata);
        $this->assertSame($data->address->city_and_district, $actual->address->cityAndDistrict);
        $this->assertSame($data->additional_info->social_media['twitter'], $actual->additionalInfo->socialMedia['twitter']);
        $this->assertSame($data->metadata['sys_log']->date, $actual->metadata['sysLog']->date);
    }
}
