<?php

namespace yii\jui\datepicker\tests;

use yii\jui\datepicker\DatePickerAsset;
use yii\helpers\FileHelper;
use yii\phpunit\TestCase;
use Yii;

class DatePickerAssetTest extends TestCase
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

    public function testBundle()
    {
        $bundle = Yii::$app->getAssetManager()->getBundle(DatePickerAsset::className());
        $this->assertInstanceOf('yii\jui\datepicker\DatePickerAsset', $bundle);
        $this->assertArrayHasKey(0, $bundle->depends);
        $this->assertEquals('yii\jui\JuiAsset', $bundle->depends[0]);
    }
}
