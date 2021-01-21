<?php

namespace app\modules\admin\models;

use app\models\auth\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\auction\Good;

/**
 * This is the model class for table "{{%good_robot}}".
 *
 * @property integer $id
 * @property integer $good_id
 * @property string $bid_time
 * @property integer $bid_interval
 * @property integer $status
 * @property integer $bid_id
 *
 * @property Good $good
 */
class GoodRobot extends \yii\db\ActiveRecord
{
    const INTERVAL_15 = 15;
    const INTERVAL_30 = 30;
    const INTERVAL_60 = 60;
    public static function arInterval() {
        return [
            static::INTERVAL_15 => '15 мин',
            static::INTERVAL_30 => '30 мин',
            static::INTERVAL_60 => '1 ч',
        ];
    }

    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_PAST = 3;
    public static function arStatus() {
        return [
            static::STATUS_NEW => 'Новый лот для торгов',
            static::STATUS_ACTIVE => 'Активные торги',
            static::STATUS_PAST => 'Торги завершены',
        ];
    }

    public static function arDummyUser() {
        $user1 = new User();
        $user2 = new User();
        $user3 = new User();
        $user1->name = 'Савелий Петрович';
        $user2->name = 'Федор113';
        $user3->name = 'Вася';
        $user1->id = -1;
        $user2->id = -2;
        $user3->id = -3;
        $user1->login = 'sav';
        $user2->login = 'fed113';
        $user3->login = 'vasia';
        $user1->setPassword('sdfh546lkndsg');
        $user2->setPassword('sdfh546lkndsg');
        $user3->setPassword('sdfh546lkndsg');
        $user1->active = $user2->active = $user3->active = User::STATUS_ACTIVE;
        $user1->phone = $user2->phone = $user3->phone = '123456';
        $user1->email = $user2->email = $user3->email = 'localhost@local.local';

        return [$user1, $user2, $user3];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'bid_time',
                'updatedAtAttribute' => 'bid_time',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getGood() {
        return $this->hasOne(Good::className(), ['id' => 'good_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%good_robot}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'bid_interval', 'status'], 'required'],
            [['good_id', 'bid_interval', 'status', 'bid_id', ], 'integer'],
            [['bid_time'], 'safe'],
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
            'bid_time' => 'Bid Time',
            'bid_interval' => 'Bid Interval',
            'status' => 'Статус',
            'bid_id' => 'ID ставки',
        ];
    }
}
