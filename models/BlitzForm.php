<?php
namespace app\models;

use app\components\MessageStatus;
use app\models\auction\Good;
use app\validators\CanDoBidValidator;
use Yii;
use yii\base\Model;
use yii\helpers\Console;

class BlitzForm extends Model
{
    public $goodId;

    /** @var Good */
    protected $goodModel;

    public function rules()
    {
        return [
            [['goodId',], 'required'],
            ['goodId', 'exist', 'targetClass' => Good::class, 'targetAttribute' => ['goodId' => 'id']],
            ['goodId', CanDoBidValidator::class],
        ];
    }

    public function run()
    {
        $this->goodModel = Good::findOne($this->goodId);
        $out = new MessageStatus();

        $bidForm = new BidForm(['goodId' => $this->goodId, 'userId' => Yii::$app->user->id, 'value' => $this->goodModel->blitz_price]);
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