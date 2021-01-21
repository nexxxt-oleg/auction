<?php

namespace app\controllers;

use app\components\CommonHelper;
use app\components\MessageStatus;
use app\components\shop\MyShoppingCart;
use app\models\auction\Good;
use app\models\auction\GoodSearch;
use app\models\auction\GoodStringSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GoodController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions'=>['view', 'index', 'search', 'preview_modal'],
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

    public $defaultAction = 'view';

    public function actionView($id) {
        /** @var  $model Good */
        $model = Good::findOne($id);
        if ($model) {
            $cart = new MyShoppingCart();
            $cart->putViewed($model);
            return $this->render('view', ['model' => $model]);
        }
        else {throw new NotFoundHttpException();}

    }

    public function actionIndex() {
        Url::remember();
        $goodSearch = new GoodSearch();
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_index', [
                'dataProvider' => $goodSearch->search($_REQUEST),
                'goodSearch' => $goodSearch,
            ]);
        }
        else {
            return $this->render('index', [
                'dataProvider' => $goodSearch->search($_REQUEST),
                'goodSearch' => $goodSearch,
            ]);
        }
    }

    public function actionSearch() {
        Url::remember();
        $goodStringSearch = new GoodStringSearch();
        $dp = $goodStringSearch->search($_REQUEST);
        $this->view->params[CommonHelper::getShortClassName(GoodStringSearch::className())] = $goodStringSearch;
        return $this->render('search', [
            'dataProvider' => $dp,
        ]);
    }

    public function actionPreview_modal($id) {
        /** @var  $model Good */
        if (!$model = Good::findOne($id)) {
            throw new NotFoundHttpException();
        }
        $cart = new MyShoppingCart();
        $cart->putViewed($model);
        return $this->renderPartial('_preview_modal', ['model' => $model]);
    }



}