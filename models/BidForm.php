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
use app\validators\CanDoBidValidator;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class BidForm extends Model
{
    public $goodId;
    public $userId;
    public $offerPrice = null;
    public $value;

    /** @var Good */
    protected $goodModel;

    public function rules()
    {
        return [
            [['goodId', 'userId'], 'required'],
            ['goodId', 'exist', 'targetClass' => Good::class, 'targetAttribute' => ['goodId' => 'id']],
            ['userId', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
            ['offerPrice', 'integer'],
            ['value', 'validateValue', 'skipOnEmpty' => false],
            ['goodId', CanDoBidValidator::class, 'userId' => $this->userId],
        ];
    }

    public function validateValue($attribute)
    {
        $this->goodModel = Good::findOne($this->goodId);
        $this->value = $this->value ?: $this->goodModel->getNextBidVal();
        if ($this->value < $this->goodModel->start_price) {
            $this->addError($attribute, 'Минимальная ставка должна быть равна ' . $this->goodModel->start_price . ' или больше');
        }

        if ($this->goodModel->max_bid) {
            $maxBid = $this->goodModel->curr_price;
            if ($maxBid && !($this->value >= $maxBid + $this->goodModel->step)) {
                $this->addError($attribute, 'Минимальная ставка должна быть равна ' . ($maxBid + $this->goodModel->step) . ' или больше');
            }
        }

        if ($this->goodModel->is_blitz_reached) {
            $this->addError($attribute, "На лот {$this->goodModel->name} предложена блитц цена. Торги по лоту приостановлены.");
        }
    }

    public function run()
    {
        $out = new MessageStatus();

        $oldMaxBid = $this->goodModel->max_bid;
        $bidModel = new Bid();
        $bidModel->value = $this->value;
        $bidModel->user_id = $this->userId;
        $bidModel->good_id = Yii::$app->request->post('goodId');
        if ($bidModel->save()) {
            $cart = new MyShoppingCart();
            $cart->put($this->goodModel);
            $out->data = ['countCart' => $cart->getCount(), 'bidVal' => $this->value];

            $this->handleRobots($bidModel);
            $this->handleOutbid($oldMaxBid);
            $this->handleMaxBid($bidModel->id);
            $this->handleBlitz($bidModel);
            $out->setTrue("На лот {$this->goodModel->name} сделана ставка $bidModel->value {$this->goodModel->auction->currency}");
            $this->handleUserPrices();
        } else {
            $out->setFalse(Html::errorSummary($bidModel));
        }


        return $out;
    }

    protected function handleMaxBid($bidId)
    {
        $maxBidForm = new MaxBidMailForm(['bidId' => $bidId]);
        if ($maxBidForm->validate()) {
            $maxBidForm->run();
        }
    }

    /**
     * @param Bid $bidModel
     */
    protected function handleBlitz($bidModel)
    {
        if ($bidModel->value >= $bidModel->good->blitz_price) {
            $bidModel->good->is_blitz_reached = true;
            $bidModel->good->save();
        }
    }



    /**
     * @param Bid $oldMaxBid
     */
    protected function handleOutbid($oldMaxBid)
    {
        if ($oldMaxBid && $oldMaxBid->user_id != $this->userId) {
            $outbidForm = new OutbidMailForm(['bidId' => $oldMaxBid]);
            if ($outbidForm->validate()) {
                $outbidForm->run();
            }
        }
    }

    protected function handleRobots($bidModel)
    {
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
    }

    protected function handleUserPrices()
    {
        /** @var GoodUserPrice $goodUserPrice */
        $goodUserPrice = GoodUserPrice::find()
            ->where(['good_id' => $this->goodId])
            ->andWhere(['>', 'price', $this->goodModel->curr_price])
            ->andWhere(['<>', 'user_id', $this->userId])
            ->orderBy('id')->one();
        if ($goodUserPrice) {
            $bidForm = new BidForm([
                'goodId' => $this->goodId,
                'userId' => $goodUserPrice->user_id,
                'value' => $this->calculateNextBid($this->offerPrice ?: $this->value)]);
            if ($bidForm->validate()) {
                $bidForm->run();
            }
        }
    }

    /**
     * Если цена следующего шага, больше чем предложенная цена, то использовать предложенную
     * @param $goodUserPrice
     * @return float|int
     */
    private function calculateNextUserPrice($goodUserPrice)
    {
        return $this->calculateNextBid($goodUserPrice->price) > $goodUserPrice->price ? $goodUserPrice->price : $this->calculateNextBid($goodUserPrice->price);
    }

    protected function calculateNextBid($levelPrice)
    {
        $nextBid = $this->goodModel->getNextBidVal();
        while ($nextBid < $levelPrice) {
            $nextBid += round($this->goodModel->step, -1);
        }
        return $nextBid;
    }
}