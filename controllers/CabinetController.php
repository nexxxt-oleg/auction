<?php

namespace app\controllers;

use app\models\auth\DeliveryForm;
use app\models\auth\User;
use app\models\auth\UserSettingsForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class CabinetController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions'=>['index'],
                        //'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        //'actions'=>['logout'],
                        'roles' => ['user'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }


    public function actionIndex() {
        $params = [];
        if (!Yii::$app->user->isGuest) {
            $userSettingsForm = new UserSettingsForm();
            if($userSettingsForm->load(Yii::$app->request->post())) {
                $userSettingsForm->saveSettings();
            } else {
                /** @var User $user */
                $user = Yii::$app->user->identity;
                $userSettingsForm->fio = $user->name;
                $userSettingsForm->phone = $user->phone;
                $userSettingsForm->email = $user->email;
            }

            $deliveryForm = new DeliveryForm();
            if($deliveryForm->load(Yii::$app->request->post())) {
                $deliveryForm->requestDelivery();
            } else {
                /** @var User $user */
                $user = Yii::$app->user->identity;
                $deliveryForm->fio = $user->name;
                $deliveryForm->phone = $user->phone;
                $deliveryForm->email = $user->email;
            }
            $params = [
                'userSettingsForm' => $userSettingsForm,
                'deliveryForm' => $deliveryForm,
            ];
        }

        return $this->render('index', $params);
    }


}