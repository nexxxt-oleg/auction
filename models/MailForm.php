<?php
namespace app\models;

use app\models\auth\User;
use yii\base\Model;

class MailForm extends Model
{
    public $userId;
    public $subject;
    public $body;
    public $mailType;

    public function rules()
    {
        return [
            [['subject', 'body'], 'required'],
            [['userId'], 'integer'],
            [['subject', 'body'], 'string'],
            ['mailType', 'in', 'range' => array_keys(Mail::arType())]
        ];
    }

    public function run()
    {
        $mailModel = new Mail([
            'type' => $this->mailType,
            'subject' => $this->subject,
            'body' => $this->body
        ]);
        /** @var User $user */
        if($user = User::findOne($this->userId)) {
            $mailModel->user_id = $user->id;
            $mailModel->user_name = $user->name;
        }

        if (!$mailModel->save()) {
            return false;
        }
        return true;
    }
}