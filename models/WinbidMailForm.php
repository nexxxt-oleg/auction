<?php
namespace app\models;

use app\models\auction\Bid;
use Yii;
use yii\base\Model;

class WinbidMailForm extends Model
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
        $subject = 'Ваша ставка победила в аукционе';
        $body = "Ваша ставка в размере {$bid->valueWithCurrency} на лот {$bid->good->name} победила в аукционе.\n";

        $mailForm = new MailForm([
            'mailType' => Mail::TYPE_WINBID,
            'userId' => $bid->user->id,
            'subject' => $subject,
            'body' => $body,
        ]);
        if ($mailForm->validate()) {
            $mailForm->run();
        }

        return Yii::$app->mailer->compose()
            ->setTo($bid->user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();
    }
}