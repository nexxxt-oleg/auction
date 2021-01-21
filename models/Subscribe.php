<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%subscribe}}".
 *
 * @property integer $id
 * @property string $email
 * @property integer $status
 */
class Subscribe extends \yii\db\ActiveRecord
{
    // по умолчанию в базу пишется активный статус
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscribe}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['status'], 'integer'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email:',
            'status' => 'Статус:',
        ];
    }
}
