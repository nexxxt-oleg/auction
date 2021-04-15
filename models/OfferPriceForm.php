<?php
namespace app\models;

use app\components\MessageStatus;
use app\models\auction\Good;
use app\models\auction\GoodUserPrice;
use app\models\auth\User;
use app\validators\CanDoBidValidator;
use Yii;
use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\Console;
use yii\helpers\Html;

class OfferPriceForm extends Model
{
    public $goodId;
    public $price;
    public $userId;

    /** @var Good */
    protected $goodModel;

    public function rules()
    {
        return [
            [['goodId', 'price', 'userId'], 'required'],
            ['price', 'integer'],
            ['price', 'validatePrice'],
            ['goodId', 'exist', 'targetClass' => Good::class, 'targetAttribute' => ['goodId' => 'id']],
            ['userId', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
            ['goodId', CanDoBidValidator::class],
        ];
    }

    public function validatePrice($attribute)
    {
        $this->goodModel = Good::findOne($this->goodId);
        $bidVal = $this->goodModel->getNextBidVal();
        if ($this->price < $bidVal) {
            $this->addError($attribute, "Предложенная цена должна быть больше минимальной ставки - $bidVal {$this->goodModel->auction->currency}");
        }
        if ($this->goodModel->is_blitz_reached) {
            $this->addError($attribute, "На лот {$this->goodModel->name} предложена блитц цена. Торги по лоту приостановлены.");
        }
        if (GoodUserPrice::find()->where(['good_id' => $this->goodId, 'price' => $this->price])->one()) {
            $this->addError($attribute, "На лот {$this->goodModel->name} ранее была предложена подобная цена. Увеличьте ставку, чтобы победить в аукционе.");
        }
    }

    public function run()
    {
        $out = new MessageStatus();

        if ($this->price == $this->goodModel->curr_price || $this->price == $this->goodModel->getNextBidVal()) {
            return $this->makeBid($out);
        }
        $gupParams = ['user_id' => $this->userId, 'good_id' => $this->goodId];
        $goodUserPrice = GoodUserPrice::find()->where($gupParams)->one();
        if (!$goodUserPrice) {
            $goodUserPrice = new GoodUserPrice($gupParams);
        }
        $goodUserPrice->price = $this->price;

        if (!$goodUserPrice->save()) {
            $out->msgError = Console::errorSummary($goodUserPrice);
            return $out;
        }
        $out->setTrue("На лот {$this->goodModel->name} предложена максимальная цена выкупа $goodUserPrice->price. Ставки до этой цены будут делаться автоматически");

        $maxBidUserId = $this->goodModel->max_bid->user_id ?? null;
        if ($maxBidUserId !== $this->userId) {
            $this->makeBid($out, ['offerPrice' => $this->price]);
        }

        return $out;
    }

    /**
     * @param MessageStatus $out
     * @return MessageStatus
     */
    private function makeBid($out, $params = [])
    {
        $bidForm = new BidForm(array_merge(['goodId' => $this->goodId, 'userId' => $this->userId], $params));
        if ($bidForm->validate()) {
            $out = $bidForm->run();
        } else {
            $bidMsg = new MessageStatus();
            $bidMsg->setFalse(Console::errorSummary($bidForm));
            $out->import($bidMsg);
        }
        return $out;
    }


}