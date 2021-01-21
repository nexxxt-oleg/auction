<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%good_cart}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $user_id
 */
class GoodCart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_cart}}';
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
