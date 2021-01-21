<?php

namespace app\commands;

use app\models\auth\User;
use Yii;
use yii\console\Controller;
use \app\components\rbac\UserRoleRule;


class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //удаляем старые данные

        //Создадим для примера права для доступа к админке
        $dashboard = $auth->createPermission('dashboard');
        $dashboard->description = 'Админ панель';
        $auth->add($dashboard);

        $rule = new UserRoleRule();
        $auth->add($rule);

        //Добавляем роли
        $user = $auth->createRole(User::ROLE_USER);
        $user->description = 'Пользователь';
        $user->ruleName = $rule->name;
        $auth->add($user);
        $moder = $auth->createRole(User::ROLE_MODER);
        $moder->description = 'Модератор';
        $moder->ruleName = $rule->name;
        $auth->add($moder);

        //Добавляем потомков
        $auth->addChild($moder, $user);
        $auth->addChild($moder, $dashboard);
        $admin = $auth->createRole(User::ROLE_ADMIN);
        $admin->description = 'Администратор';
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $moder);
    }

}
