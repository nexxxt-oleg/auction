<?php
namespace app\models\auth;
use app\models\Mail;
use app\models\MailForm;
use yii\base\Model;
use Yii;
use yii\bootstrap\Html;

/**
 * Delivery form
 */
class DeliveryForm extends Model
{
    public $fio;
    public $phone;
    public $email;
    public $address;
    public $comment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            [['fio', 'phone', 'address', 'comment' ], 'required'],
            ['email', 'filter', 'filter' => 'trim'],
//            [['fio', 'phone', 'address', 'comment'], 'safe'],

        ];
    }

    public function attributeLabels() {
        return [
            'fio' => 'ФИО:',
            'phone' => 'Телефон:',
            'email' => 'Email:',
            'password' => 'Пароль:',
            'address' => 'Адрес:',
            'comment' => 'Комментарий:',
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function requestDelivery() {
        if ($this->validate()) {
            $attributesStr = '';
            foreach ($this as $name => $value) {
                $attributesStr .= "$name: $value\n";
            }
            $subject = "Оформлена доставка для $this->fio";
            $body = "Оформлена доставка. Параметры:\n".$attributesStr;
            Yii::$app->mailer->compose()
                ->setTo(Yii::$app->params['adminEmail'])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setSubject($subject)
                ->setTextBody("Оформлена доставка. Параметры:\n".$attributesStr)
                ->send();
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setSubject($subject)
                ->setTextBody("Оформлена доставка. Параметры:\n".$attributesStr)
                ->send();

            $mailForm = new MailForm([
                'mailType' => Mail::TYPE_DELIVERY,
                'userId' => Yii::$app->user->identity->getId(),
                'subject' => $subject,
                'body' => $body,
            ]);
            if ($mailForm->validate()) {
                return $mailForm->run();
            }

        }
        return false;
    }
}