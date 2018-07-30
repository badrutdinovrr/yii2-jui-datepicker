<?php

namespace yii\jui\datepicker;

use yii\helpers\FormatConverter as BaseFormatConverter;

class FormatConverter extends BaseFormatConverter
{

    /**
     * @param string $pattern
     * @param string $type
     * @param string $locale
     * @return string
     */
    public static function convertDatePhpOrIcuToJui($pattern, $type = 'date', $locale = null)
    {
        if (strncmp($pattern, 'php:', 4) === 0) {
            return static::convertDatePhpToJui(substr($pattern, 4));
        } else {
            return static::convertDateIcuToJui($pattern, $type, $locale);
        }
    }
}
