<?php

namespace yii\jui\datepicker\tests;

use yii\jui\datepicker\DatePickerLanguageFixAsset;
use yii\helpers\FileHelper;
use yii\phpunit\TestCase;
use Yii;

class DatePickerLanguageFixAssetTest extends TestCase
{

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        foreach (glob(Yii::$app->getAssetManager()->basePath . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR) as $dir) {
            FileHelper::removeDirectory($dir);
            $this->assertFalse(is_dir($dir));
        }
    }

    /**
     * @return array
     */
    public function defaultLanguageDataProvider()
    {
        return [
            ['en-US'], ['en']
        ];
    }

    /**
     * @param string $language
     * @dataProvider defaultLanguageDataProvider
     */
    public function testBundleNotHasFile($language)
    {
        Yii::$app->language = $language;
        $bundle = Yii::$app->getAssetManager()->getBundle(DatePickerLanguageFixAsset::className());
        $this->assertInstanceOf('yii\jui\datepicker\DatePickerLanguageFixAsset', $bundle);
        $this->assertArrayHasKey(0, $bundle->depends);
        $this->assertEquals('yii\jui\datepicker\DatePickerLanguageAsset', $bundle->depends[0]);
        $this->assertArrayNotHasKey(0, $bundle->js);
    }

    /**
     * @return array
     */
    public function languageDataProvider()
    {
        return [
            ['af'], ['ar-DZ'], ['ar'], ['az'], ['be'],
            ['bg'], ['bs'], ['ca'], ['cs'], ['cy-GB'],
            ['da'], ['de'], ['el'], ['en-AU'], ['en-GB'],
            ['en-NZ'], ['eo'], ['es'], ['et'], ['eu'],
            ['fa'], ['fi'], ['fo'], ['fr-CA'], ['fr-CH'],
            ['fr'], ['gl'], ['he'], ['hi'], ['hr'],
            ['hu'], ['hy'], ['id'], ['is'], ['it-CH'],
            ['it'], ['ja'], ['ka'], ['kk'], ['km'],
            ['ko'], ['ky'], ['lb'], ['lt'], ['lv'],
            ['mk'], ['ml'], ['ms'], ['nb'], ['nl-BE'],
            ['nl'], ['nn'], ['no'], ['pl'], ['pt-BR'],
            ['pt'], ['rm'], ['ro'], ['ru'], ['sk'],
            ['sl'], ['sq'], ['sr'], ['sr-SR'], ['sv'],
            ['ta'], ['th'], ['tj'], ['tr'], ['uk'],
            ['vi'], ['zh-CN'], ['zh-HK'], ['zh-TW']
        ];
    }

    /**
     * @param string $language
     * @dataProvider languageDataProvider
     */
    public function testBundleHasFile($language)
    {
        Yii::$app->language = $language;
        $bundle = Yii::$app->getAssetManager()->getBundle(DatePickerLanguageFixAsset::className());
        $this->assertInstanceOf('yii\jui\datepicker\DatePickerLanguageFixAsset', $bundle);
        $this->assertArrayHasKey(0, $bundle->depends);
        $this->assertEquals('yii\jui\datepicker\DatePickerLanguageAsset', $bundle->depends[0]);
        $this->assertArrayHasKey(0, $bundle->js);
        $this->assertFileExists($bundle->basePath . DIRECTORY_SEPARATOR . $bundle->js[0]);
    }
}
