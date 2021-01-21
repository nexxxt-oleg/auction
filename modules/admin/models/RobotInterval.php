<?php

namespace app\modules\admin\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%robot_interval}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $value
 */
class RobotInterval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%robot_interval}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'name',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'name',
                ],
                'value' => function ($event) {
                    return "{$event->sender->value} минутный";
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'value' => 'Интервал в минутах',
        ];
    }
}
