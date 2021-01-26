<?php
namespace app\models;

use app\components\MessageStatus;
use app\models\auction\Good;
use app\models\auction\GoodUserPrice;
use Yii;
use yii\base\Model;
use yii\helpers\Console;
use yii\helpers\Html;

class OfferPriceForm extends Model
{
    public $goodId;
    public $price;

    public function rules()
    {
        return [
            [['goodId', 'price'], 'required'],
            ['price', 'integer'],
            ['price', 'validatePrice'],
            ['goodId', 'exist', 'targetClass' => Good::class, 'targetAttribute' => ['goodId' => 'id']],
        ];
    }

    public function validatePrice($attribute)
    {
        $goodModel = Good::findOne($this->goodId);
        $bidVal = $goodModel->getNextBidVal();
        if ($this->price < $bidVal) {
            $this->addError($attribute, "Предложенная цена должна быть больше минимальной ставки - $bidVal рублей");
        }
    }

    public function run()
    {
        $out = new MessageStatus();

        $gupParams = ['user_id' => Yii::$app->user->identity->getId(), 'good_id' => $this->goodId];
        $goodUserPrice = GoodUserPrice::find()
            ->where($gupParams)->one();
        if (!$goodUserPrice) {
            $goodUserPrice = new GoodUserPrice($gupParams);
        }
        $goodUserPrice->price = $this->price;

        if (!$goodUserPrice->save()) {
            $out->msgError = Console::errorSummary($goodUserPrice);
            return $out;
        }

        $bidForm = new BidForm(['goodId' => $this->goodId, 'userId' => Yii::$app->user->identity->getId()]);
        if ($bidForm->validate()) {
            $bidMsg = $bidForm->run();
        } else {
            $bidMsg = new MessageStatus();
            $bidMsg->setFalse(Console::errorSummary($bidForm));
        }
        $out->import($bidMsg);
        return $out;
    }


}