<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;
use app\models\MailForm;
use Yii;
use app\models\Mail;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\i18n\Formatter;

/**
 * CommonHelper provides functionality that you can use in your
 * application.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CommonHelper
{
    public static function er($param)
    {
        if($param === false)
            error_log("false\n",3,Yii::$app->basePath.DIRECTORY_SEPARATOR.'error.log');
        elseif(is_null($param)) {error_log("NULL\n",3,Yii::$app->basePath.DIRECTORY_SEPARATOR.'error.log');}
        elseif(gettype($param) != 'string') {error_log(print_r($param,true)."\n",3,Yii::$app->basePath.DIRECTORY_SEPARATOR.'error.log');}

        else
            error_log($param."\n",3,Yii::$app->basePath.DIRECTORY_SEPARATOR.'error.log');
    }

    /** @param mixed $className <p>
     * Either a string containing the name of the class to
     * reflect, or an object.
     * @return string short name of class
     */
    public static function getShortClassName($className) {
        $reflection = new \ReflectionClass($className);
        return $reflection->getShortName();
    }

    public static function mail_log ($subj, $body) {
        $mailForm = new MailForm([
            'mailType' => Mail::TYPE_SYSTEM_LOG,
            'subject' => $subj,
            'body' => $body,
        ]);
        if ($mailForm->validate()) {
            $mailForm->run();
        }
    }

    /**
     * @param ActiveRecord $model
     * @param string|[] $attribute Название атрибута или массив из нескольких названий для получения связанных полей.
     * Например, ['good', 'auction', 'end_date'] --- $goodRobot->good->auction->end_date
     * @return int метка времени unix epoch
     */
    public static function getUnixEpoch($model = null, $attribute = null) {
        $timestamp = false;
        $getNowFlag = false;
        if ($model && $attribute) {
            if (is_array($attribute)) {
                $prop = $model;
                foreach ($attribute as $attrVal) {
                    if ($prop->$attrVal) {
                        $prop = $prop->$attrVal;
                    } else {
                        $getNowFlag = true;
                        break;
                    }
                }
            } else {
                if ($model->$attribute) {
                    $prop = $model->$attribute;
                } else {
                    $getNowFlag = true;
                }
            }
            if (!$getNowFlag) {
                $strTime = Yii::$app->getFormatter()->asDate($prop, 'php:Y-m-d H:i:s');
            }
        } else {
            $getNowFlag = true;
        }
        if ($getNowFlag) {
            $f = new \yii\i18n\Formatter();
            $f->timeZone = 'GMT+3';
            $strTime = $f->asDate(time(), 'php:Y-m-d H:i:s');
        }
        $timestamp = strtotime ($strTime);
        return $timestamp;
    }

}