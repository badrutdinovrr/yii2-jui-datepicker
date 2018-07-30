<?php

namespace yii\jui\datepicker;

use yii\web\AssetBundle;
use Yii;

class DatePickerLanguageAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery-ui/ui/i18n';

    public $depends = ['yii\jui\datepicker\DatePickerAsset'];

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
        if (is_null($this->language)) {
            $this->language = Yii::$app->language;
            if (is_null($this->language)) {
                $this->language = 'en-US';
            }
        }
        if (($this->language != 'en-US') && ($this->language != 'en')) {
            $sourcePath = Yii::getAlias($this->sourcePath);
            $jsFile = 'datepicker-' . $this->language . '.js';
            if (is_file($sourcePath . DIRECTORY_SEPARATOR . $jsFile)) {
                $this->js[] = $jsFile;
            } elseif ((strlen($this->language) == 5) && (strncmp($this->language, 'en', 2) != 0)) {
                $jsFile = 'datepicker-' . substr($this->language, 0, 2) . '.js';
                if (is_file($sourcePath . DIRECTORY_SEPARATOR . $jsFile)) {
                    $this->js[] = $jsFile;
                }
            }
        }
    }
}
