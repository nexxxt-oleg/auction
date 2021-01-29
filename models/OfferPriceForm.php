<?php
namespace app\models;

use app\components\MessageStatus;
use app\models\auction\Good;
use app\models\auction\GoodUserPrice;
use app\models\auth\User;
use Yii;
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
        ];
    }

    public function validatePrice($attribute)
    {
        $this->goodModel = Good::findOne($this->goodId);
        $bidVal = $this->goodModel->getNextBidVal();
        if ($this->price < $bidVal) {
            $this->addError($attribute, "Предложенная цена должна быть больше минимальной ставки - $bidVal рублей");
        }
    }

    public function run()
    {
        $out = new MessageStatus();

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

        $winBidUserId = $this->goodModel->win_bid->user_id ?? null;
        if ($winBidUserId !== $this->userId) {
            $bidForm = new BidForm(['goodId' => $this->goodId, 'userId' => $this->userId, 'isOfferBid' => false]);
            if ($bidForm->validate()) {
                $bidForm->run();
            } else {
                $bidMsg = new MessageStatus();
                $bidMsg->setFalse(Console::errorSummary($bidForm));
                $out->import($bidMsg);
            }
        }

        return $out;
    }


}