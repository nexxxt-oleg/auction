<?php

namespace app\modules\admin\controllers;

use app\components\MessageStatus;
use Yii;
use app\models\auth\User;
use app\modules\admin\models\search\UserSearch;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AdminuserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//        print_r($model->attributes);
        $postData = Yii::$app->request->post($model->formName());
        if (isset($postData['password']) && empty($postData['password'])) {
            unset($postData['password']);
        }
        if ($model->load($postData, '')) {
//            print_r($model->attributes);
//            die();
            if ($password = $postData['password']) {

                $model->setPassword($password);

            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * update active
     * @return string
     */
    public function actionUpdate_active()
    {
        $out = new MessageStatus();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->get('user_id') && Yii::$app->request->get('active_id') != null) {
            /** @var User $user */
            $user = User::findOne(Yii::$app->request->get('user_id'));
            $arActive = User::arActive();
            if ($user && isset($arActive[Yii::$app->request->get('active_id')])) {
                $user->active = Yii::$app->request->get('active_id');
                if ($user->save()) {
                    if ($user->active == User::STATUS_ACTIVE) {
                        $auth = Yii::$app->authManager;
                        $arUserRole = $auth->getRolesByUser($user->id);
                        $role = $auth->getRole('user');
                        if ($role && !in_array($role, $arUserRole)) {
                            $auth->assign($role, $user->id);
                        }
                    }
                    $out->setTrue("Пользователю '$user->name' установлен статус активности '" . $arActive[Yii::$app->request->get('active_id')] . "'");
                } else {
                    $out->setFalse(Html::errorSummary($user));
                }
            } else {
                $out->setFalse("Не найден пользователь(" . Yii::$app->request->get('user_id') . ") или не существует статус активности (" . Yii::$app->request->get('active_id') . ")");
            }
        } else {
            $out->setFalse('Не найдены обязательные параметры: user_id или active_id');
        }
        return $out;
    }

    /**
     * reset password
     * @return string
     */
    public function actionReset_password()
    {
        $dummyPassword = '123456';
        $out = new MessageStatus();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->get('user_id')) {
            /** @var User $user */
            $user = User::findOne(Yii::$app->request->get('user_id'));
            if ($user) {
                $user->setPassword($dummyPassword);
                if ($user->save()) {
                    $out->setTrue("Пользователю '$user->name' установлен пароль '$dummyPassword'");
                } else {
                    $out->setFalse(Html::errorSummary($user));
                }
            } else {
                $out->setFalse("Не найден пользователь(" . Yii::$app->request->get('user_id') . ")");
            }
        } else {
            $out->setFalse('Не найдены обязательные параметры: user_id');
        }
        return $out;
    }
}
