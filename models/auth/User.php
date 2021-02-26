<?php

namespace app\models\auth;

use app\components\CommonHelper;
use app\models\auction\Bid;
use app\models\Mail;
use app\models\MailForm;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\mail\BaseMailer;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $auction_name
 * @property string $email
 * @property string $name
 * @property string $phone
 * @property string $info
 * @property string $active
 * @property string $add_time
 * @property string $password_reset_token
 * @property string $auth_key
 *
 * @property Bid[] $bids
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'email', 'phone', 'active'], 'required'],
            [['add_time'], 'default', 'value'=>date('d.m.Y'), ],
            [['add_time'], 'safe'],
            [['login', 'auction_name', 'email', 'name', 'phone', 'info'], 'string', 'max' => 255],
            [['password'], 'safe', 'skipOnEmpty' => true,],
            [['active'], 'string', 'max' => 1],
            [['password_reset_token', 'auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'auction_name' => 'Auction Name',
            'email' => 'Email',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'info' => 'Info',
            'active' => 'Статус активности',
            'add_time' => 'Add Time',
            'password_reset_token' => 'Password Reset Token',
            'auth_key' => 'Auth Key',
        ];
    }

    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['user_id' => 'id']);
    }

    const STATUS_ACTIVE = '1';
    const STATUS_DELETED = '0';
    public static function arActive() {
        return [
            static::STATUS_ACTIVE => 'Активен',
            static::STATUS_DELETED => 'Удален',
        ];
    }
    public static function printActive($i) {
        $arActive = static::arActive();
        return isset($arActive[$i]) ? $arActive[$i] : "Неизвестный статус ($i)";
    }

    const ROLE_USER = 'user';
    const ROLE_MODER = 'moder';
    const ROLE_ADMIN = 'admin';

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'active' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        //CommonHelper::er(__METHOD__.__LINE__);
        return static::findOne(['login' => $username, 'active' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString(6) . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'active' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Sends an email with a link, for registeration of new user.
     *
     * @return boolean whether the email was send
     */
    public function sendNewRegEmail($password)
    {
        if ($this->email) {
            $subject = \Yii::$app->name.': зарегистрирован новый пользователь ';

            $message = \Yii::$app->mailer->compose('newUserWarstory', ['user' => $this, 'password' => $password])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                ->setTo($this->email)
                ->setSubject($subject )
                ->send();

            $mailForm = new MailForm([
                'mailType' => Mail::TYPE_NEW_USER,
                'userId' => $this->getId(),
                'subject' => $subject,
                'body' => \Yii::$app->mailer->render('newUserWarstory', ['user' => $this, 'password' => $password]),
            ]);
            if ($mailForm->validate()) {
                return $mailForm->run();
            }

            return $message;
        }
        return false;
    }

    public function isAdmin() {
        $auth = Yii::$app->authManager;
        $arUserRole = $auth->getRolesByUser($this->id);
        $role = $auth->getRole('admin');
        if ($role && in_array($role, $arUserRole)) {
            return true;
        }
        return false;
    }

    public function isActive()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->active == User::STATUS_ACTIVE;
    }
}
