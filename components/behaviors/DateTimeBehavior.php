<?php
namespace app\components\behaviors;

use app\components\CommonHelper;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class DateTimeBehavior extends Behavior
{
    public $dtAttributes;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'convertDateTimeAttrs',
            ActiveRecord::EVENT_AFTER_FIND => 'convertDateTimeAttrs',
        ];
    }


    public function convertDateTimeAttrs() {
        //CommonHelper::er(__METHOD__.__LINE__);
        foreach ($this->dtAttributes as $attrName) {
            //CommonHelper::er($attrName);
            //CommonHelper::er($this->owner->$attrName);
            if ($this->owner->$attrName) {
                $this->owner->$attrName = \Yii::$app->formatter->asDatetime($this->owner->$attrName, Yii::$app->params['dateControlDisplay'][\kartik\datecontrol\Module::FORMAT_DATETIME]);
            }
            //CommonHelper::er($this->owner->$attrName);
        }
    }


}