<?php

namespace app\modules\admin\controllers;

use app\components\MessageStatus;
use app\modules\admin\models\search\BidSearch;
use app\models\auction\Auction;
use app\models\auction\Category;
use Yii;
use app\models\auction\Good;
use app\modules\admin\models\search\GoodSearch;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AdminpriceController implements the CRUD actions for Good model.
 */
class PriceController extends Controller
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
                        'actions' => ['view', 'index', 'Update_active', 'reset_password'],
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
     * Lists all Good models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
     // Check if there is an Editable ajax request
        if (Yii::$app->request->post('hasEditable')) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $out = ['output'=>'', 'message'=>''];
            if ($model = Good::findOne(Yii::$app->request->post('editableKey'))) {
                $reflector = new \ReflectionClass(Good::className());
                $gooded = current($_POST[$reflector->getShortName()]);
                $good = [$reflector->getShortName() => $gooded];
                if ($model->load($good)) {
                    $model->save();
                    $output = '';
                    if (in_array(Yii::$app->request->post('editableAttribute'), ['start_price', 'accept_price', 'mpc_price', 'blitz_price'])) {
                        $output = Yii::$app->formatter->asDecimal($model->{Yii::$app->request->post('editableAttribute')}, 2);
                    }
                    $out = ['output'=>$output, 'message'=>''];
                }
            }
            return $out;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Good model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new BidSearch();
        $dataProvider = $searchModel->searchByGood(Yii::$app->request->queryParams, $id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Good model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Good();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Good model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Good model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Good model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Good the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Good::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
