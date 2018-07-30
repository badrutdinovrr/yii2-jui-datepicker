<?php

namespace yii\jui\datepicker\tests;

use yii\jui\datepicker\FormatConverter;
use yii\phpunit\TestCase;

class FormatConverterTest extends TestCase
{

    /**
     * @return array
     */
    public function formatsDataProvider()
    {
        return [
            ['php:Y-m-d', 'yyyy-MM-dd', 'yy-mm-dd'], // 1952-12-13
            ['php:m/d/y', 'MM/dd/yy', 'mm/dd/y'], // 12/13/52
            ['php:M. d, Y', 'MMM. dd, yyyy', 'M. dd, yy'], // Jan. 12, 1952
            ['php:F d, Y', 'MMMM dd, yyyy', 'MM dd, yy'] // January 12, 1952
        ];
    }

    /**
     * @param string $phpFormat
     * @param string $icuFormat
     * @param string $juiFormat
     * @dataProvider formatsDataProvider
     */
    public function testConvertDatePhpOrIcuToJui($phpFormat, $icuFormat, $juiFormat)
    {
        $this->assertEquals($juiFormat, FormatConverter::convertDatePhpOrIcuToJui($phpFormat));
        $this->assertEquals($juiFormat, FormatConverter::convertDatePhpOrIcuToJui($icuFormat));
    }
}
