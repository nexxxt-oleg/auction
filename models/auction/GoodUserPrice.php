<?php

namespace app\models\auction;

use app\models\auth\User;
use Yii;

/**
 * This is the model class for table "au_good_user_price".
 *
 * @property int $id
 * @property int $user_id
 * @property int $good_id
 * @property int $price Цена за которою пользователь готов выкупить лот
 *
 * @property User $user
 * @property Good $good
 */
class GoodUserPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'au_good_user_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'good_id', 'price'], 'required'],
            [['user_id', 'good_id', 'price'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'good_id' => 'Good ID',
            'price' => 'Price',
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
}
