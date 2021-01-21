<?php
namespace app\models\auth;
use app\models\Mail;
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

            $mailModel = new Mail();
            $mailModel->type = Mail::TYPE_DELIVERY;
            /** @var User $user */
            if($user = Yii::$app->user->identity) {
                $mailModel->user_id = $user->getId();
                $mailModel->user_name = $user->name;
            }
            $mailModel->subject = $subject;
            $mailModel->body = $body;
            if (!$mailModel->save()) {return false;}
            return true;
        } else {return false;}
    }
}