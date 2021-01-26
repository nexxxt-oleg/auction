<?php

namespace app\models;

use app\components\CommonHelper;
use app\components\MessageStatus;
use app\components\shop\MyShoppingCart;
use app\models\auction\Bid;
use app\models\auction\Good;
use app\models\auction\GoodUserPrice;
use app\models\auth\User;
use app\modules\admin\models\GoodRobot;
use app\modules\admin\models\RobotInterval;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class BidForm extends Model
{
    public $goodId;
    public $userId;

    public function rules()
    {
        return [
            [['goodId', 'userId'], 'required'],
            ['goodId', 'exist', 'targetClass' => Good::class, 'targetAttribute' => ['goodId' => 'id']],
            ['userId', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    public function run()
    {
        $out = new MessageStatus();

        /** @var Good $goodModel */
        $goodModel = Good::findOne($this->goodId);
        $bidVal = $goodModel->getNextBidVal();
        if ($bidVal < ($goodModel->start_price + $goodModel->step)) {
            $out->setFalse('Минимальная ставка должна быть равна ' . ($goodModel->start_price + $goodModel->step) . ' или больше');
            return $out;
        }
        $maxBid = $goodModel->curr_price;
        if ($maxBid && !($bidVal >= $maxBid + $goodModel->step)) {
            $out->setFalse('Минимальная ставка должна быть равна ' . ($maxBid + $goodModel->step) . ' или больше');
            return $out;
        }

        $bidModel = new Bid();
        $bidModel->value = $bidVal;
        $bidModel->user_id = $this->userId;
        $bidModel->good_id = Yii::$app->request->post('goodId');
        if ($bidModel->save()) {
            $cart = new MyShoppingCart();
            $cart->put($goodModel);
            $out->data = ['countCart' => $cart->getCount(), 'bidVal' => $bidVal];

            if (!$goodRobot = GoodRobot::findOne(['good_id' => $bidModel->good_id])) {
                $goodRobot = new GoodRobot();
                $goodRobot->good_id = $bidModel->good_id;
                $goodRobot->status = GoodRobot::STATUS_NEW;
                $goodRobot->bid_interval = array_rand(RobotInterval::find()->indexBy('value')->all());
                if (!$goodRobot->save()) {
                    $subject = "Ошибка при попытке создать запись " . $goodRobot->className();
                    $body = "Ошибка при попытке создать запись " . $goodRobot->className() . ": good_id - $goodRobot->good_id. \n";
                    $body .= Html::errorSummary($goodRobot);
                    CommonHelper::mail_log($subject, $body);
                }
            }
            $out->setTrue("На лот $goodModel->name сделана ставка $bidModel->value");
            $this->handleUserPrices();
        } else {
            $out->setFalse(Html::errorSummary($bidModel));
        }


        return $out;
    }

    protected function handleUserPrices()
    {
        /** @var Good $goodModel */
        $goodModel = Good::findOne($this->goodId);

        /** @var GoodUserPrice[] $goodUserPrices */
        $goodUserPrices = GoodUserPrice::find()
            ->where(['good_id' => $this->goodId])
            ->andWhere(['>=', 'price', $goodModel->getNextBidVal()])
            ->andWhere(['<>', 'user_id', $this->userId])
            ->orderBy('id')->all();
        foreach ($goodUserPrices as $goodUserPrice) {
            $bidForm = new BidForm(['goodId' => $this->goodId, 'userId' => $goodUserPrice->user_id]);
            if ($bidForm->validate()) {
                $bidForm->run();
            }
        }
    }
}