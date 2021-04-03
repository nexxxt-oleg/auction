<?php
namespace app\models;

use app\models\auction\Bid;
use app\models\auth\User;
use Yii;
use yii\base\Model;

class OutbidMailForm extends Model
{
    public $bidId;

    public function rules()
    {
        return [
            ['bidId', 'exist', 'targetClass' => Bid::class, 'targetAttribute' => ['bidId' => 'id']],
        ];
    }

    public function run()
    {
        $bid = Bid::findOne($this->bidId);
        $toUser = User::findOne($bid->user_id);
        $subject = 'Ваша ставка перебита';
        $body = "Ваша ставка в размере {$bid->valueWithCurrency} на лот {$bid->good->name} перебита ставкой {$bid->good->max_bid->valueWithCurrency}.\n";
        $body .= "Дата окончания аукциона: {$bid->good->auction->end_date}";
        $mailForm = new MailForm([
            'mailType' => Mail::TYPE_OUTBID,
            'userId' => $bid->user_id,
            'subject' => $subject,
            'body' => $body,
        ]);
        if ($mailForm->validate()) {
            return $mailForm->run();
        }

        return Yii::$app->mailer->compose()
            ->setTo($toUser->email)
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();
    }
}