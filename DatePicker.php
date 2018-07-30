<?php

namespace yii\jui\datepicker;

use DateTime;
use DateTimeInterface;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii\base\InvalidParamException;
use yii\web\JsExpression;
use yii\helpers\Json;
use Yii;

class DatePicker extends InputWidget
{

    /**
     * @var string
     * @see http://api.jqueryui.com/datepicker/#option-dateFormat
     * @see http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#$dateFormat-detail
     * @uses \yii\i18n\Formatter::$dateFormat
     */
    public $dateFormat;

    /**
     * @var string
     * @see http://api.jqueryui.com/datepicker/#option-altFormat
     * @see http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#$dateFormat-detail
     */
    public $altDateFormat;

    /**
     * @var int
     * @see http://api.jqueryui.com/datepicker/#option-numberOfMonths
     */
    public $numberOfMonths = 1;

    /**
     * @var bool
     * @see http://api.jqueryui.com/datepicker/#option-showButtonPanel
     */
    public $showButtonPanel = false;

    /**
     * @var array
     */
    public $altOptions = [];

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (is_null($this->dateFormat)) {
            $this->dateFormat = Yii::$app->getFormatter()->dateFormat;
            if (is_null($this->dateFormat)) {
                $this->dateFormat = 'medium';
            }
        }
        if (is_null($this->altDateFormat)) {
            $this->altDateFormat = 'yyyy-MM-dd';
        }
        Html::addCssClass($this->options, 'form-control');
        $this->altOptions['id'] = $this->options['id'] . '-alt';
        $this->clientOptions = array_merge(array_diff_assoc([
            'numberOfMonths' => $this->numberOfMonths,
            'showButtonPanel' => $this->showButtonPanel
        ], get_class_vars(__CLASS__)), $this->clientOptions, [
            'dateFormat' => FormatConverter::convertDatePhpOrIcuToJui($this->dateFormat),
            'altField' => '#' . $this->altOptions['id'],
            'altFormat' => FormatConverter::convertDatePhpOrIcuToJui($this->altDateFormat)
        ]);
        if (array_key_exists('readonly', $this->options) && $this->options['readonly']) {
            if (!array_key_exists('beforeShow', $this->clientOptions)) {
                $this->clientOptions['beforeShow'] = new JsExpression('function (input, inst) { return false; }');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $hasModel = $this->hasModel();
        if ($hasModel) {
            if (array_key_exists('value', $this->options)) {
                $value = $this->options['value'];
            } else {
                $value = Html::getAttributeValue($this->model, $this->attribute);
            }
        } else {
            $value = $this->value;
        }
        if (is_int($value) || (is_string($value) && strlen($value))
            || ($value instanceof DateTime) || ($value instanceof DateTimeInterface)
        ) {
            $formatter = Yii::$app->getFormatter();
            try {
                $altValue = $formatter->asDate($value, $this->altDateFormat);
                $value = $formatter->asDate($value, $this->dateFormat);
            } catch (InvalidParamException $e) {
                $altValue = $value;
            }
        } else {
            $altValue = $value;
        }
        if ($hasModel) {
            $this->options = array_merge($this->options, [
                'name' => false,
                'value' => $value
            ]);
            $this->altOptions['value'] = $altValue;
            $output = Html::activeTextInput($this->model, $this->attribute, $this->options);
            $output .= Html::activeHiddenInput($this->model, $this->attribute, $this->altOptions);
        } else {
            $output = Html::textInput(false, $value, $this->options);
            $output .= Html::hiddenInput($this->name, $altValue, $this->altOptions);
        }
        $js = 'jQuery(\'#' . $this->options['id'] . '\').datepicker(' . Json::htmlEncode($this->clientOptions) . ');';
        if (Yii::$app->getRequest()->getIsAjax()) {
            $output .= Html::script($js);
        } else {
            $view = $this->getView();
            DatePickerAsset::register($view);
            DatePickerLanguageAsset::register($view);
            DatePickerLanguageFixAsset::register($view);
            $view->registerJs($js);
        }
        return $output;
    }
}
