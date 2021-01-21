<?php
namespace app\models\auth;
use yii\base\Model;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /** @var User */
    public $user;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['active' => User::STATUS_ACTIVE],
                'message' => 'Пользователя с таким email не существует.'
            ],
        ];
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        $this->user = User::findOne([
            'active' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
        if ($this->user) {
            if (!User::isPasswordResetTokenValid($this->user->password_reset_token)) {
                $this->user->generatePasswordResetToken();
            }
            if ($this->user->save()) {
                return \Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $this->user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Восстановление пароля для ' . \Yii::$app->name)
                    ->send();
            }
        }
        return false;
    }
}