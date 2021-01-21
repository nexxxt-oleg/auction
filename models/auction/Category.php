<?php

namespace app\models\auction;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property integer $priority
 * @property string $active
 *
 * @property Auction[] $auctions
 */
class Category extends \yii\db\ActiveRecord
{
    const ACTIVE_ACTIVE = 'Y';
    const ACTIVE_NO = 'N';
    public static function getArActive () {
        return [
            self::ACTIVE_ACTIVE => 'Активный',
            self::ACTIVE_NO => 'Не активный',
        ];
    }

    public function getAuctions()
    {
        return $this->hasMany(Auction::className(), ['id' => 'auction_id'])
            ->viaTable(AuctionCategories::tableName(), ['category_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'priority', 'active'], 'required'],
            [['priority'], 'integer'],
            [['name', 'description', 'url'], 'string', 'max' => 255],
            [['active'], 'string', 'max' => 1],
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
            'description' => 'Описание',
            'url' => 'Url',
            'priority' => 'Приоритет',
            'active' => 'Активность',
            'auctions' => 'Аукционы'
        ];
    }

}
