<?php

namespace app\models\auction;

use app\models\auth\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%bid}}".
 *
 * @property integer $id
 * @property string $value
 * @property integer $user_id
 * @property integer $good_id
 * @property string $date
 *
 * @property User $user
 * @property Good $good
 */
class Bid extends \yii\db\ActiveRecord
{
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getGood()
    {
        return $this->hasOne(Good::className(), ['id' => 'good_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bid}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'user_id', 'good_id'], 'required'],
            [['value'], 'number'],
            [['user_id', 'good_id'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'user_id' => 'User ID',
            'good_id' => 'Good ID',
            'date' => 'Date',
        ];
    }
}
