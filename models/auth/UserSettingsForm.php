<?php
namespace app\models\auth;
use app\models\auth\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
/**
 * Password reset form
 */
class UserSettingsForm extends Model
{
    public $fio;
    public $phone;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'unique',
                'targetClass' => '\app\models\auth\User',
                'message' => 'Пользователь с таким email уже зарегистрирован'
            ],
            [['fio', 'phone', ], 'safe'],

        ];
    }

    public function attributeLabels() {
        return [
            'fio' => 'ФИО:',
            'phone' => 'Телефон:',
            'email' => 'Email:',
            'password' => 'Пароль:',
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function saveSettings() {
        if ($this->validate()) {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            if ($this->fio) {$user->name = $this->fio;}
            if ($this->phone) {$user->phone = $this->phone;}
            if ($this->email) {$user->email = $this->email;}
            if ($this->password) {
                $user->setPassword($this->password);
            }
            return $user->save(false);
        } else {return false;}
    }
}