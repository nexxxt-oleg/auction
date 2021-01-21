<?php
namespace app\models\auth;

use app\components\CommonHelper;
use yii\base\Model;
use Yii;
use yii\base\Security;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $fio;
    public $email;
    public $phone;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['fio', 'filter', 'filter' => 'trim'],
            ['fio', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\auth\User', 'message' => 'Такой email уже используется'],
            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'fio' => 'ФИО:',
            'phone' => 'Телефон:',
            'email' => 'Email:',
        ];
    }



    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $security = new Security();
        $user = new User();
        $user->login = $user->email = $this->email;
        $user->name = $this->fio;
        $user->phone = ($this->phone) ? $this->phone : null;
        $user->active = User::STATUS_DELETED;
        $user->add_time = date('d.m.Y');

        $realPassword = $security->generateRandomString(6);
        $user->setPassword($realPassword);
        $user->generateAuthKey();

        if ($user->save()) {
            $user->sendNewRegEmail($realPassword);
            return $user;
        } else {
            return null;
        }
    }
}