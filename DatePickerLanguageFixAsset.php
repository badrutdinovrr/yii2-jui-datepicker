<?php

namespace yii\jui\datepicker;

use yii\web\AssetBundle;
use yii\helpers\Json;
use Yii;

class DatePickerLanguageFixAsset extends AssetBundle
{

    public $depends = ['yii\jui\datepicker\DatePickerLanguageAsset'];

    /**
     * @var string
     * @see http://www.yiiframework.com/doc-2.0/yii-base-application.html#$language-detail
     * @uses \yii\base\Application::$language
     */
    public $language;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->sourcePath = get_class_vars(DatePickerLanguageAsset::className())['sourcePath'];
        if (is_null($this->language)) {
            $this->language = Yii::$app->language;
            if (is_null($this->language)) {
                $this->language = 'en-US';
            }
        }
        if (($this->language != 'en-US') && ($this->language != 'en')) {
            $sourcePath = Yii::getAlias($this->sourcePath);
            $intlLoaded = extension_loaded('intl');
            $version = $intlLoaded ? ('intl-' . phpversion('intl')) : 'fix';
            $jsFile = 'datepicker-' . $this->language . '-' . $version . '.js';
            if (!is_file($sourcePath . DIRECTORY_SEPARATOR . $jsFile)) {
                $clientOptions = [
                    'monthNames' => [],
                    'monthNamesShort' => [],
                    'dayNames' => [],
                    'dayNamesShort' => [],
                    'dayNamesMin' => []
                ];
                $formatter = Yii::$app->getFormatter();
                foreach (array_map(function ($month) {
                    return mktime(0, 0, 0, $month);
                }, range(1, 12)) as $timestamp) {
                    $clientOptions['monthNames'][] = $formatter->asDate($timestamp, 'MMMM');
                    $clientOptions['monthNamesShort'][] = $formatter->asDate($timestamp, 'MMM');
                }
                foreach (array_map(function ($day) {
                    return mktime(0, 0, 0, 11, $day, 2015);
                }, range(1, 7)) as $timestamp) {
                    $clientOptions['dayNames'][] = $formatter->asDate($timestamp, 'EEEE');
                    $clientOptions['dayNamesShort'][] = $formatter->asDate($timestamp, 'EEE');
                    if ($intlLoaded) {
                        $clientOptions['dayNamesMin'][] = $formatter->asDate($timestamp, 'EEEEEE');
                    }
                }
                $data = 'jQuery.datepicker.setDefaults(' . Json::htmlEncode($clientOptions) . ');';
                file_put_contents($sourcePath . DIRECTORY_SEPARATOR . $jsFile, $data);
            }
            $this->js[] = $jsFile;
        }
    }
}
