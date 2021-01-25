<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 22.07.2016
 * Time: 15:56
 */

namespace app\controllers;


use app\components\CommonHelper;
use app\components\MessageStatus;
use app\components\shop\MyShoppingCart;
use app\modules\admin\models\GoodRobot;
use app\modules\admin\models\RobotInterval;
use app\models\auction\Bid;
use app\models\auction\Good;
use app\models\auction\GoodFavorite;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yz\shoppingcart\ShoppingCart;

class BasketController extends Controller {
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions'=>['addToCart', 'bid', 'favorite'],
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

    public function actionBid() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = new MessageStatus();
        if (Yii::$app->request->post('goodId')) {
            /** @var Good $goodModel */
            $goodModel = Good::findOne(Yii::$app->request->post('goodId'));
            if (!$goodModel) {
                $out->setFalse('Не найден лот '. Yii::$app->request->post('goodId'));
                return $out;
            }
            $goodModel->validate();
            $goodPrice = $goodModel->curr_price ?: $goodModel->start_price;
            $bidVal = $goodPrice + $goodModel->step;
            if ($bidVal < ($goodModel->start_price + $goodModel->step)) {
                $out->setFalse('Минимальная ставка должна быть равна '.($goodModel->start_price + $goodModel->step).' или больше');
                return $out;
            }
            $maxBid = $goodModel->curr_price;
            if ($maxBid && !($bidVal >= $maxBid + $goodModel->step)) {
                $out->setFalse('Минимальная ставка должна быть равна '.($maxBid + $goodModel->step).' или больше');
                return $out;
            }

            $bidModel = new Bid();
            $bidModel->value = $bidVal;
            $bidModel->user_id = Yii::$app->user->identity->getId();
            $bidModel->good_id = Yii::$app->request->post('goodId');
            if ($bidModel->save()) {
                $cart = new MyShoppingCart();
                $cart->put($goodModel);
                $out->data = ['countCart' => $cart->getCount(), 'bidVal' => $bidVal];

                if(!$goodRobot = GoodRobot::findOne(['good_id' => $bidModel->good_id])) {
                    $goodRobot = new GoodRobot();
                    $goodRobot->good_id = $bidModel->good_id;
                    $goodRobot->status = GoodRobot::STATUS_NEW;
                    $goodRobot->bid_interval = array_rand(RobotInterval::find()->indexBy('value')->all());
                    if(!$goodRobot->save()) {
                        $subject = "Ошибка при попытке создать запись ".$goodRobot->className();
                        $body = "Ошибка при попытке создать запись ".$goodRobot->className().": good_id - $goodRobot->good_id. \n";
                        $body .= Html::errorSummary($goodRobot);
                        CommonHelper::mail_log($subject, $body);
                    }
                }
                $out->setTrue("На лот $goodModel->name сделана ставка $bidModel->value");
            } else {
                $out->setFalse(Html::errorSummary($bidModel));
            }
        } else {
            $out->setFalse('Не найден обязательный параметр goodId');
        }

        return $out;
    }

    public function actionFavorite() {
        $out = new MessageStatus();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->request->post('goodId')) {
            /** @var Good $good */
            if (!$good = Good::findOne(Yii::$app->request->post('goodId'))) {
                $out->setFalse("В бд не найден good ". Yii::$app->request->post('goodId'));
                return $out;
            }
            $cart = new MyShoppingCart();
            if (Yii::$app->request->post('action') == 'remove') {
                $cart->removeFavorite($good);
                $out->setTrue("$good->name изъят из избранного");
            } else {
                $cart->putFavorite($good);
                $out->setTrue("$good->name успешно добавлен в избранное");
            }

        } else {$out->setFalse('Не найден параметр goodId');}
        return $out;
    }


}