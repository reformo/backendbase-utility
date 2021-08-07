<?php

declare(strict_types=1);

namespace UnitTest;

use Codeception\Test\Unit;
use Selami\Stdlib\Exception\InvalidArgumentException;
use Selami\Stdlib\Slugifier;

class SlugifierTest extends Unit
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
     * @dataProvider dataProvider
     */
    public function shouldReturnSlugifySuccessfully($subjects, $expected): void
    {
        $actual = Slugifier::slugify($subjects);
        $index  = 0;
        foreach ($actual as $item) {
            $this->assertEquals($expected[$index], $item, 'Returning expected slugs failed');
            $index++;
        }
    }
    /**
     * @test
     */
    public function shouldThrowExceptionForInvalidInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Slugifier::slugify(1234);
    }

    public function dataProvider() : array
    {
        return [
                    [
                        [
                            'Meinung: Impfstoff-Mangel für die Ärmsten - eine moralische Bankrotterklärung',
                            'Türkiye\'nin İstanbul Sözleşmesi serüveni',
                            'Во Франции прошли протесты против ужесточения мер',
                            '1234'
                        ],
                        [
                            'meinung-impfstoff-mangel-fur-die-armsten-eine-moralische-bankrotterklarung',
                            'turkiye-nin-istanbul-sozlesmesi-seruveni',
                            'vo-francii-prosli-protesty-protiv-uzestocenia-mer',
                            '1234'
                        ],
                    ]
        ];
    }

}
