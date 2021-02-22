<?php

namespace app\models;

use app\models\auth\User;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $body;
    public $verifyCode;
    public $type;
    public $phone;
    protected $subject;

    const TYPE_QUESTION = 1;
    const TYPE_COMMENT = 2;
    const TYPE_CALLBACK = 3;
    const TYPE_COMMON = 4;
    const TYPE_REQUEST = 5;

    public static function getTypes()
    {
        return [
            'Задать вопрос' => self::TYPE_QUESTION,
            'Оставить отзыв' => self::TYPE_COMMENT,
            'Заявка на обратный звонок' => self::TYPE_CALLBACK,
            'Задать вопрос /Оставить предложение' => self::TYPE_COMMON,
            'Выкуп лота' => self::TYPE_REQUEST,
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            ['type', 'in', 'range' => self::getTypes()],
            ['phone', 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Проверочный код',
            'name' => 'ФИО:',
            'email' => 'Email:',
            'type' => 'Тип формы (влияет на внешний вид и тело письма)',
            'body' => 'Вопрос / Преложение:',
            'phone' => 'Телефон:',

        ];
    }

    public function printBodyLabel()
    {
        switch ($this->type) {
            case self::TYPE_COMMON:
            default:
                return 'Вопрос / Преложение:';
                break;
            case self::TYPE_QUESTION:
                return 'Вопрос:';
                break;
            case self::TYPE_COMMENT:
                return 'Отзыв:';
                break;
            case self::TYPE_CALLBACK:
                return 'Обратный звонок:'; // не используется
                break;
            case self::TYPE_REQUEST:
                return 'Заявка на выкуп лота:';
                break;
        }
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $fromEmail the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($fromEmail)
    {
        if (!$this->type) {
            $this->type = self::TYPE_COMMON;
        }
        if ($this->validate()) {
            $body = '';
            switch ($this->type) {
                case self::TYPE_COMMON:
                default:
                    $this->subject = "Обращение от $this->email";
                    $body = "Поступило обращение от $this->name.\n";
                    $body .= $this->phone ? "Телефон: $this->phone.\n" : '';
                    $body .= "Email: $this->email.\n";
                    $body .= "Текст обращения: \n---$this->body\n---\n";
                    break;
                case self::TYPE_QUESTION:
                    $this->subject = "Вопрос от $this->email";
                    $body = "Поступил вопрос от $this->name.\n";
                    $body .= $this->phone ? "Телефон: $this->phone.\n" : '';
                    $body .= "Email: $this->email.\n";
                    $body .= "Текст вопроса: \n---$this->body\n---\n";
                    break;
                case self::TYPE_COMMENT:
                    $this->subject = "Отзыв от $this->email";
                    $body = "Поступил отзыв от $this->name.\n";
                    $body .= $this->phone ? "Телефон: $this->phone.\n" : '';
                    $body .= "Email: $this->email.\n";
                    $body .= "Текст отзыва: \n---$this->body\n---\n";
                    break;
                case self::TYPE_CALLBACK:
                    $this->email = $fromEmail;
                    $this->subject = "Заявка на обратный звонок от $this->phone";
                    $body = "Поступила заявка на обратный звонок от $this->phone.\n";
                    $body .= "Телефон: $this->phone.\n";
                    break;
                case self::TYPE_REQUEST:
                    $this->subject = "Заявка на выкуп от $this->email";
                    $body = "Поступила заявка на выкуп лота $this->name.\n";
                    $body .= $this->phone ? "Телефон: $this->phone.\n" : '';
                    $body .= "Email: $this->email.\n";
                    $body .= "Текст обращения: \n---$this->body\n---\n";
                    break;
            }
            $this->body = $body;

            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setFrom([$fromEmail => Yii::$app->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            $mailForm = new MailForm([
                'mailType' => Mail::TYPE_CONTACT_FORM,
                'userId' => Yii::$app->user->identity->getId(),
                'subject' => $this->subject,
                'body' => $this->body,
            ]);
            if ($mailForm->validate()) {
                return $mailForm->run();
            }

        }

        return false;
    }
}