<?php

namespace app\models\auction;

use app\components\shop\MyShoppingCart;
use app\models\auth\User;
use Yii;

/**
 * This is the model class for table "{{%auction}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $active_date
 * @property string $start_date
 * @property string $end_date
 * @property string $active
 *
 * @property Category[] $categories
 * @property Good[] $goods
 * @property User[] $users
 */
class Auction extends \yii\db\ActiveRecord
{
    const ACTIVE_FLAG = 'Y';
    const NEAREST_FLAG = 'A';
    const DISABLE_FLAG = 'N';
    const VISIBLE_FLAG = 'V';
    const PAST_FLAG = 'P';

    public static function getArActive () {
        return [
            self::ACTIVE_FLAG => 'Активный',
            self::NEAREST_FLAG => 'Ближайший',
            self::DISABLE_FLAG => 'Отключен',
            self::VISIBLE_FLAG => 'Видимый',
            self::PAST_FLAG => 'Прошедший',
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction}}';
    }

    /**
     * @return User[]
     */
    public function getUsers() {
        return User::findBySql('SELECT distinct u.*
            FROM au_auction au
              INNER JOIN au_good g ON g.auction_id = au.id
              INNER JOIN au_bid b ON b.good_id = g.id
              INNER JOIN au_user u ON b.user_id = u.id
            WHERE au.id = 11 and u.id > 0')->all();
    }

    public function getUsersCurrAuction() {
        return User::findBySql('SELECT distinct u.*
            FROM au_auction au
              INNER JOIN au_good g ON g.auction_id = au.id
              INNER JOIN au_bid b ON b.good_id = g.id
              INNER JOIN au_user u ON b.user_id = u.id
            WHERE au.id = '.$this->id)->all();
    }

    public function getAr_view_favor_bid() {
        $arOut = ['viewedCount' => 0, 'favoriteCount' => 0, 'bidCount' => 0];
        $arOutBidCount = User::findBySql('SELECT distinct b.*
            FROM au_auction au
              INNER JOIN au_good g ON g.auction_id = au.id
              INNER JOIN au_bid b ON b.good_id = g.id
            WHERE au.id = '.$this->id)->count();

        /** @var MyShoppingCart $arCart */
        foreach ($this->users as $user) {
            $cart = new MyShoppingCart(['userId' => $user->id]);
            $arOut['favoriteCount'] += $cart->getCountFavorite($this->id);
            $arOut['viewedCount'] += $cart->getCountViewed($this->id);
        }
        $arOut['bidCount'] = $arOutBidCount;
        return $arOut;
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable(AuctionCategories::tableName(), ['auction_id' => 'id']);
    }

    public function getGoods() {
        return $this->hasMany(Good::className(), ['auction_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'start_date', 'end_date', 'active'], 'required'],
            [['active_date', 'start_date', 'end_date'], 'safe'],
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
            'active_date' => 'Дата активации (служебное)',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата конца',
            'active' => 'Флаг активности',
        ];
    }
}
