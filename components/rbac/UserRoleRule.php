<?php
namespace app\components\rbac;

use app\components\CommonHelper;
use Yii;
use yii\rbac\Rule;
use yii\helpers\ArrayHelper;
use app\models\auth\User;

class UserRoleRule extends Rule
{
    public $name = 'userRole';

    public function execute($user, $item, $params)
    {
        $user = ArrayHelper::getValue($params, 'user', User::findOne($user));
        if ($user) {
            $arAssign = Yii::$app->authManager->getAssignments($user->id);
            $firstAssign = array_shift($arAssign); // todo: решение не сработает, если у пользователя несколько ролей
            if (!isset($firstAssign->roleName)) {return false;}
            $role = $firstAssign->roleName; //Значение из assignments.php
            if ($item->name === USER::ROLE_ADMIN) {
                return $role == User::ROLE_ADMIN;
            } elseif ($item->name === USER::ROLE_MODER) {
                //moder является потомком admin, который получает его права
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER;
            }
            elseif ($item->name === USER::ROLE_USER) {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_MODER
                || $role == User::ROLE_USER;
            }
        }
        return false;
    }
}