<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%auction_goods}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $auction_id
 */
class AuctionGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'auction_id'], 'required'],
            [['good_id', 'auction_id'], 'integer'],
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
            'auction_id' => 'Auction ID',
        ];
    }
}
