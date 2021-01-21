<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%good_viewed}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $user_id
 */
class GoodViewed extends \yii\db\ActiveRecord
{
    const ACTION_ADD = 'add';
    const ACTION_REMOVE = 'remove';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_viewed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'user_id'], 'required'],
            [['good_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_id' => 'Good ID',
            'user_id' => 'User ID',
        ];
    }
}
