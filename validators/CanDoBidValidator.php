<?php

namespace app\validators;

use app\models\auction\Good;
use app\models\auth\User;
use Yii;
use yii\validators\Validator;

class CanDoBidValidator extends Validator
{
    public $userId;

    public function validateAttribute($model, $attribute)
    {
        $this->userId = $this->userId ?: Yii::$app->user->id;
        $user = User::findOne($this->userId);
        if (!$goodModel = Good::findOne($model->$attribute)) {
            $this->addError($model, $attribute, "Не найден лот {$model->$attribute}");
        }
        if (!$goodModel->canDoBid($user)) {
            $this->addError($model, $attribute, 'Вы не можете сделать ставку на этот лот');
        }
    }
}