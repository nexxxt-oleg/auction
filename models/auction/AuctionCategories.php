<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%auction_categories}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $auction_id
 */
class AuctionCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'auction_id'], 'required'],
            [['category_id', 'auction_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'auction_id' => 'Auction ID',
        ];
    }
}
