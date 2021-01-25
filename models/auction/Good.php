<?php

namespace app\models\auction;

use app\components\shop\MyCartPositionTrait;
use app\models\auth\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yz\shoppingcart\CartPositionInterface;

/**
 * This is the model class for table "{{%good}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $auction_id
 * @property integer $category_id
 * @property integer $start_price
 * @property integer $accept_price
 * @property integer $end_price
 * @property integer $mpc_price
 * @property integer $blitz_price
 * @property integer $curr_bid_id
 * @property integer $win_bid_id
 * @property integer $status
 * @property integer $type
 * @property integer $sell_rule
 * @property integer $add_time
 * @property integer $step
 *
 * @property Bid[] $bids
 * @property Filter[] $filters
 * @property Bid $win_bid
 * @property Bid $max_bid
 * @property string $max_bid_user
 * @property integer $curr_price
 * @property integer $user_bid
 * @property string $img_path
 * @property array $extra_img_paths
 *
 * @property Auction $auction
 * @property Category $category
 * @property GoodViewed[] $good_viewed
 * @property GoodFavorite[] $good_favorite
 *
 */
class Good extends \yii\db\ActiveRecord implements CartPositionInterface
{
    const TYPE_COMMON = 1;
    const TYPE_INDEX = 2;

    const SELL_RULE_ANY = 1;
    const SELL_RULE_MIN = 2;
    const SELL_RULE_NO = 3;

    const STATUS_INIT = 0;
    const STATUS_SOLD = 1;
    const STATUS_NOT_SOLD = 2;
    const STATUS_SOLD_TO_ADMIN = 3;

    public $filter;

    /**
     * @var UploadedFile[]
     */
    public $extraImages;
    /**
     * @var UploadedFile
     */
    public $mainImage;

    use MyCartPositionTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'auction_id', 'category_id', 'start_price', 'accept_price', 'status'], 'required'],
            [['auction_id', 'category_id', 'start_price', 'accept_price', 'end_price', 'curr_bid_id', 'win_bid_id', 'status', 'type',
                'sell_rule', 'filter', 'mpc_price', 'blitz_price', 'step'], 'integer'],
            [['step'], 'default', 'value' => $this->getDefaultStep()],
            [['name',], 'string', 'max' => 255],
            [['mainImage', 'extraImages', 'add_time'], 'safe'],
            [['mainImage'], 'file', 'extensions' => ['png', 'jpg', 'gif']],
            [['extraImages'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxFiles' => 0],
            ['sell_rule', 'default', 'value' => static::SELL_RULE_ANY],
        ];
    }

    public function getPrice()
    {
        return (!Yii::$app->user->isGuest) ? $this->user_bid : $this->curr_price;
    }

    public function getId()
    {
        return $this->id;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'add_time',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function arSellRule()
    {
        return [
            static::SELL_RULE_ANY => 'За любую цену',
            static::SELL_RULE_MIN => 'За минимальную цену',
            static::SELL_RULE_NO => 'Не продается',
        ];
    }

    public static function printSellRule($i)
    {
        $arSellRule = static::arSellRule();
        return isset($arSellRule[$i]) ? $arSellRule[$i] : "Неизвестное правило ($i)";
    }

    public static function arType()
    {
        return [
            static::TYPE_COMMON => 'Обычный товар',
            static::TYPE_INDEX => 'Товар показывается на главной',
        ];
    }

    public static function printType($i)
    {
        $arType = static::arType();
        return isset($arType[$i]) ? $arType[$i] : "Неизвестный тип ($i)";
    }

    public static function arStatus()
    {
        return [
            static::STATUS_INIT => 'Не учавствовал в аукционе',
            static::STATUS_SOLD => 'Продано',
            static::STATUS_NOT_SOLD => 'Не продано',
            static::STATUS_SOLD_TO_ADMIN => 'Продано администратору',
        ];
    }

    public static function printStatus($i)
    {
        $arStatus = static::arStatus();
        return isset($arStatus[$i]) ? $arStatus[$i] : "Неизвестный статус ($i)";
    }

    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['good_id' => 'id']);
    }

    public function getMax_bid()
    {
        return $this->getBids()->orderBy(['value' => SORT_DESC])->one();
    }

    public function getMax_bid_user()
    {
        /** @var Bid $maxBid */
        $maxBid = $this->getBids()->orderBy(['value' => SORT_DESC])->one();
        return $maxBid->user ? "{$maxBid->user->name} ({$maxBid->user->id})" : 'Не найден';
    }

    public function getDefaultStep()
    {
        return intval($this->start_price * 0.05);
    }

    public function getCurr_price()
    {
        return $this->max_bid ? $this->max_bid->value : $this->start_price;
    }

    public function getAuction()
    {
        return $this->hasOne(Auction::className(), ['id' => 'auction_id']);
    }

    public function getFilters()
    {
        return $this->hasMany(Filter::className(), ['id' => 'filter_id'])
            ->viaTable(FilterGood::tableName(), ['good_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getWin_bid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'win_bid_id']);
    }

    public function getUser_bid()
    {
        return $this->getBids()->where(['user_id' => Yii::$app->user->getId()])->max('value');
    }

    public function getImg_path()
    {
        if (file_exists(Yii::getAlias("@app/assets_b/img/lot/$this->id.jpg"))) {
            $path = "/assets_b/img/lot/$this->id.jpg";
        } elseif (file_exists(Yii::getAlias("@app/assets_b/img/lot/$this->id.JPG"))) {
            $path = "/assets_b/img/lot/$this->id.JPG";
        } else {
            $path = "/assets_b/img/icon/no_image.png";
        }
        return $path;
    }

    public function getExtra_img_paths()
    {
        $arFiles = [];
        if (is_dir(Yii::getAlias("@app/assets_b/img/lot/$this->id"))) {
            $arFiles = \yii\helpers\FileHelper::findFiles(Yii::getAlias("@app/assets_b/img/lot/$this->id"), ['only' => ['*.jpg', '*.JPG']]);
            foreach ($arFiles as &$file) {
                $file = "/assets_b/img/lot/$this->id/" . basename($file);
            }
        }
        return $arFiles;
    }

    public function canDoBid()
    {
        return $this->auction && $this->auction->active == \app\models\auction\Auction::ACTIVE_FLAG
            && !$this->win_bid_id &&
            (!Yii::$app->user->isGuest && Yii::$app->user->identity->active == User::STATUS_ACTIVE);
    }

    public function getGood_viewed()
    {
        return $this->hasMany(GoodViewed::className(), ['good_id' => 'id']);
    }

    public function getGood_favorite()
    {
        return $this->hasMany(GoodFavorite::className(), ['good_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'description' => 'Описание',
            'auction_id' => 'Аукцион',
            'category_id' => 'Категория',
            'start_price' => 'Начальная цена',
            'accept_price' => 'Минимальная цена',
            'mpc_price' => 'МПЦ цена',
            'blitz_price' => 'Блитц цена',
            'end_price' => 'Конечная цена',
            'curr_bid_id' => 'Curr Bid ID',
            'win_bid_id' => 'Win Bid ID',
            'status' => 'Статус',
            'type' => 'Тип (для главной страницы)',
            'sell_rule' => 'Правило продажи',
            'bid_count' => 'Кол-во ставок',
            'max_bid' => 'Максимальная ставка',
            'max_bid_user' => 'Пользователь',
            'bid_date' => 'Время ставки',
            'mainImage' => 'Основное изображение',
            'extraImages' => 'Дополнительные изображения',
            'filters' => "Фильтр (категория) - фильтр-родитель",
            'add_time' => 'Дата добавления'
        ];
    }

    public function uploadImages()
    {
        if (!is_dir(Yii::getAlias("@app/assets_b/img/lot/$this->id"))) {
            mkdir(Yii::getAlias("@app/assets_b/img/lot/$this->id"));
        }
        if ($this->mainImage) {
            $this->mainImage->saveAs(Yii::getAlias("@app/assets_b/img/lot/$this->id.{$this->mainImage->extension}"));
        }
        foreach ($this->extraImages as $file) {
            $path = Yii::getAlias("@app/assets_b/img/lot/$this->id") . "/$file->name";
            $file->saveAs($path);
        }

    }


}
