<?php
namespace app\models;

use app\models\auction\Bid;
use Yii;
use yii\base\Model;

class MaxBidMailForm extends Model
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
        $subject = 'Ваша ставка является максимальной';
        $body = "Ваша ставка в размере {$bid->value} на лот {$bid->good->name} является максимальной.\n";
        $body .= "Дата окончания аукциона: {$bid->good->auction->end_date}";
        Yii::$app->mailer->compose()
            ->setTo($bid->user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();

        $mailForm = new MailForm([
            'mailType' => Mail::TYPE_MAXBID,
            'userId' => $bid->user->id,
            'subject' => $subject,
            'body' => $body,
        ]);
        if ($mailForm->validate()) {
            return $mailForm->run();
        }
        return false;
    }
}