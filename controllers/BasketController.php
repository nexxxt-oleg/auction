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
use app\models\BidForm;
use app\models\BlitzForm;
use app\models\OfferPriceForm;
use app\modules\admin\models\GoodRobot;
use app\modules\admin\models\RobotInterval;
use app\models\auction\Bid;
use app\models\auction\Good;
use app\models\auction\GoodFavorite;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\helpers\Console;
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
                        'actions'=>['addToCart', 'bid', 'favorite', 'offer'],
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
        $form = new BidForm(['userId' => Yii::$app->user->identity->getId()]);
        $form->load(Yii::$app->request->post(), '');
        if ($form->validate()) {
            $out = $form->run();
            return $out;
        }
        $out = new MessageStatus();
        $out->setFalse(Console::errorSummary($form));
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

    public function actionOffer()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = new OfferPriceForm(['userId' => Yii::$app->user->identity->getId()]);
        $form->load(Yii::$app->request->post(), '');
        if ($form->validate()) {
            $out = $form->run();
            return $out;
        }
        $out = new MessageStatus();
        $out->setFalse(Console::errorSummary($form));
        return $out;
    }

    public function actionBlitz()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $form = new BlitzForm();
        $form->load(Yii::$app->request->post(), '');
        if ($form->validate()) {
            $out = $form->run();
            return $out;
        }
        $out = new MessageStatus();
        $out->setFalse(Console::errorSummary($form));
        return $out;
    }
}