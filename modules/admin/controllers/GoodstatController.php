<?php

namespace app\modules\admin\controllers;

use app\components\CommonHelper;
use app\components\MessageStatus;
use app\modules\admin\models\search\BidSearch;
use app\modules\admin\models\search\GoodStat;
use app\models\auction\Auction;
use app\models\auction\Category;
use app\modules\admin\models\GoodRobot;
use app\models\auction\Filter;
use Yii;
use app\models\auction\Good;
use app\modules\admin\models\search\GoodSearch;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * AdmingoodstatController implements index action for Good model.
 */
class GoodstatController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['viwed', 'favorite', 'bids'],
                        'allow' => true,
                        'roles' => ['moder'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Lists viewed Good models for auction.
     * @return mixed
     */
    public function actionViewed()
    {
        $searchModel = new GoodStat();
        $dataProvider = $searchModel->searchViwed(Yii::$app->request->queryParams);

        return $this->render('viewed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists favorite Good models for auction.
     * @return mixed
     */
    public function actionFavorite()
    {
        $searchModel = new GoodStat();
        $dataProvider = $searchModel->searchFavorite(Yii::$app->request->queryParams);

        return $this->render('favorite', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists bids on Good models for auction.
     * @return mixed
     */
    public function actionBids()
    {
        $searchModel = new GoodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
