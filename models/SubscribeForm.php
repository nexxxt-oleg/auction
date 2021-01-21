<?php

namespace app\models;

use app\models\auth\User;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SubscribeForm extends Subscribe
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return array_merge([
            ['email', 'email'],
        ], parent::rules());
    }

    /**
     * Добавить пользователя в базу подписки и выслать ему сообщение об успешной активации подписки
     * @return boolean whether the model passes validation
     */
    public function subscribe()
    {
        if ($this->save()) {
            $subject = Yii::$app->name.": подписка активна";
            $body = "Благодарим за подписку на новости аукциона военного антиквариата.";
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setSubject($subject)
                ->setTextBody($body)
                ->send();

            Yii::$app->mailer->compose()
                ->setTo(Yii::$app->params['adminEmail'])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setSubject("Добавлена подписка")
                ->setTextBody("Пользователь $this->email добавлен в список рассылки.")
                ->send();

            $mailModel = new Mail();
            $mailModel->type = Mail::TYPE_SUBSCRIBE;
            /** @var User $user */
            if($user = Yii::$app->user->identity) {
                $mailModel->user_id = $user->getId();
                $mailModel->user_name = $user->name;
            }

            $mailModel->subject ="Добавлена подписка";
            $mailModel->body = "Пользователь $this->email добавлен в список рассылки.";
            if (!$mailModel->save()) {
                return false;
            }
            return true;
        }
        return false;
    }
}