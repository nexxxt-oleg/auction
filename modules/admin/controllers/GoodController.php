<?php

namespace app\modules\admin\controllers;

use app\components\CommonHelper;
use app\components\MessageStatus;
use app\modules\admin\models\search\BidSearch;
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
 * AdmingoodController implements the CRUD actions for Good model.
 */
class GoodController extends Controller
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
                        'actions' => ['view', 'index', 'link_auction', 'link_category', 'update_sellrule', 'link_filter'],
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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->extraImages = UploadedFile::getInstances($model, 'extraImages');
                $model->mainImage = UploadedFile::getInstance($model, 'mainImage');
                $model->uploadImages();
                if (Yii::$app->request->post('create_another', false) !== false) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model->extraImages = UploadedFile::getInstances($model, 'extraImages');
                $model->mainImage = UploadedFile::getInstance($model, 'mainImage');
                $model->uploadImages();
                return $this->redirect(['view', 'id' => $model->id]);
            }


        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Good model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->img_path) {
            $path = null;
            if (file_exists(Yii::getAlias("@app/assets_b/img/lot/$model->id.jpg"))) {
                $path = Yii::getAlias("@app/assets_b/img/lot/$model->id.jpg");
            } elseif (file_exists(Yii::getAlias("@app/assets_b/img/lot/$model->id.JPG"))) {
                $path = Yii::getAlias("@app/assets_b/img/lot/$model->id.JPG");
            }
            if ($path) {
                unlink($path);
            }
        }
        if (is_dir(Yii::getAlias("@app/assets_b/img/lot/$model->id"))) {
            FileHelper::removeDirectory(Yii::getAlias("@app/assets_b/img/lot/$model->id"));
        }
        $model->delete();

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

    /**
     * Link good to auction
     * @return string
     */
    public function actionLink_auction() {
        $out = new MessageStatus();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->get('good_id') && Yii::$app->request->get('auction_id')) {
            /** @var Good $good */
            /** @var Auction $auction */
            $good = Good::findOne(Yii::$app->request->get('good_id'));
            $auction = Auction::findOne(Yii::$app->request->get('auction_id'));
            if ($good && $auction) {
                $good->link('auction', $auction);
                $out->setTrue("Лот '$good->name' привязан к аукциону '$auction->name'");
            } else {$out->setFalse("Не найден лот(".Yii::$app->request->get('good_id').") или аукцион(".Yii::$app->request->get('auction_id').")");}
        } else {$out->setFalse('Не найдены обязательные параметры: good_id или auction_id');}
        return $out;
    }

    /**
     * Link good to category
     * @return string
     */
    public function actionLink_category() {
        $out = new MessageStatus();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->get('good_id') && Yii::$app->request->get('category_id')) {
            /** @var Good $good */
            /** @var Category $category */
            $good = Good::findOne(Yii::$app->request->get('good_id'));
            $category = Category::findOne(Yii::$app->request->get('category_id'));
            if ($good && $category) {
                $good->link('category', $category);
                $out->setTrue("Лот '$good->name' привязан к категории '$category->name'");
            } else {$out->setFalse("Не найден лот(".Yii::$app->request->get('good_id').") или категория(".Yii::$app->request->get('category_id').")");}
        } else {$out->setFalse('Не найдены обязательные параметры: good_id или category_id');}
        return $out;
    }

    /**
     * update sell_rule
     * @return string
     */
    public function actionUpdate_sellrule() {
        $out = new MessageStatus();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->get('good_id') && Yii::$app->request->get('rule_id')) {
            /** @var Good $good */
            /** @var Category $category */
            $good = Good::findOne(Yii::$app->request->get('good_id'));
            $arSellRule = Good::arSellRule();
            if ($good && isset($arSellRule[Yii::$app->request->get('rule_id')])) {
                $good->sell_rule = Yii::$app->request->get('rule_id');
                if ($good->save()) {
                    if ($good->sell_rule == Good::SELL_RULE_ANY) {
                        if($goodRobot = GoodRobot::findOne(['good_id' => $good->id])) {
                            $goodRobot->delete();
                        }
                    }
                    $out->setTrue("Лоту '$good->name' установлено правило продажи '".$arSellRule[Yii::$app->request->get('rule_id')]."'");
                } else {$out->setFalse(Html::errorSummary($good));}
            } else {$out->setFalse("Не найден лот(".Yii::$app->request->get('good_id').") или не существует правило продажи (".Yii::$app->request->get('category_id').")");}
        } else {$out->setFalse('Не найдены обязательные параметры: good_id или rule_id');}
        return $out;
    }

    /**
     * Link good to filter
     * @return string
     */
    public function actionLink_filter() {
        $out = new MessageStatus();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->get('good_id') && Yii::$app->request->get('ar_filter')) {
            /** @var Good $good */
            $good = Good::findOne(Yii::$app->request->get('good_id'));
            $good->unlinkAll('filters', true);
            foreach (Yii::$app->request->get('ar_filter') as $filter_id) {
                /** @var Filter $filter */
                $filter = Filter::findOne($filter_id);
                if ($good && $filter) {
                    $good->link('filters', $filter);
                    $out->setTrue("Лот '$good->name' привязан к фильтру '$filter->name'.\n");
                } else {$out->setFalse("Не найден лот(".Yii::$app->request->get('good_id').") или фильтр(".$filter_id.")");}
            }
        } else {$out->setFalse('Не найдены обязательные параметры: good_id или filter_id');}
        return $out;
    }
}
