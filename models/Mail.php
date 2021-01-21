<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%mail}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_name
 * @property string $subject
 * @property string $body
 * @property string $date
 * @property integer $type
 */
class Mail extends \yii\db\ActiveRecord
{
    const TYPE_NEW_USER = 1;
    const TYPE_GOOD_SOLD = 2;
    const TYPE_CONTACT_FORM = 3;
    const TYPE_DELIVERY = 4;
    const TYPE_OTHER = 5;
    const TYPE_SUBSCRIBE = 6;
    const TYPE_SYSTEM_LOG = 7;

    public static function arType() {
        return [
            static::TYPE_NEW_USER => 'Регистрация нового пользователя',
            static::TYPE_GOOD_SOLD => 'Товар продан',
            static::TYPE_CONTACT_FORM => 'Форма обратной связи',
            static::TYPE_DELIVERY => 'Доставка',
            static::TYPE_OTHER => 'Другое',
            static::TYPE_SUBSCRIBE => 'Подписка',
            static::TYPE_SYSTEM_LOG => 'Системные сообщения',
        ];
    }
    public static function printType($i) {
        $arType = static::arType();
        return isset($arType[$i]) ? $arType[$i] : "Неизвестный тип ($i)";
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'body', 'type'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['body'], 'string'],
            [['date'], 'safe'],
            [['user_name'], 'string', 'max' => 255],
            [['subject'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'ID пользователя',
            'user_name' => 'Имя пользователя',
            'subject' => 'Тема',
            'body' => 'Тело письма',
            'date' => 'Время',
            'type' => 'Тип письма',
        ];
    }
}
