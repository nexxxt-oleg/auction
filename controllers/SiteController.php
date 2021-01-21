<?php

namespace app\controllers;

use app\models\auction\Category;
use app\models\auth\PasswordResetRequestForm;
use app\models\auth\ResetPasswordForm;
use app\models\auth\User;
use app\models\CallBackForm;
use app\models\ContactForm;
use app\models\auth\LoginForm;
use app\models\auth\SignupForm;
use app\models\Subscribe;
use app\models\SubscribeForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions'=>['index','login','error','signup', 'confirm_registration', 'contact','faq','captcha',
                            'contacts', 'call_back', 'subscribe', 'logout', 'request-password-reset', 'reset-password'],
                        //'roles' => ['?'],
                    ],
                    /*
                    [
                        'allow' => true,
                        'actions'=>['logout'],
                        'roles' => ['user'],
                    ],*/
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'foreColor'=>0x62804a,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new LoginForm();
        return $this->renderPartial('index', [
            'loginModel' => $model,
        ]);
    }



    public function actionContacts() {
        $model = new ContactForm();
        $model->type = isset($_REQUEST[$model->formName()]['type']) ? $_REQUEST[$model->formName()]['type'] : null;
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            return $this->render('contacts_success', [
                'model' => $model,
            ]);
        }
        return $this->render('contacts', [
            'model' => $model,
        ]);
    }

    public function actionCall_back() {
        $model = new CallBackForm();

        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            return $this->render('contacts_success', [
                'model' => $model,
            ]);
        }
        return $this->render('contacts', [
            'model' => $model,
        ]);
    }

    public function actionFaq()
    {
        return $this->render('faq');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->render('signup_success', [
                    'userModel' => $user,
                ]);
            }
        }

        return $this->render('signup', [
            'signUpModel' => $model,
        ]);
    }

    public function actionConfirm_registration($auth_key) {
        $arMsg = [
            'title' => 'Ошибка при подтверждении регистрации',
            'css_class' => 'text-error',
            'msg' => 'При попытке подтвердить регистрацию учетной записи произошла ошибка. Проверьте правильность ссылки.',
        ];
        /** @var  $user User */
        $user = User::find()->where(['auth_key' => $auth_key])->one();
        if ($user) {
            $user->active = User::STATUS_ACTIVE;
            $user->auth_key = null;
            if ($user->save()) {
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole('user');
                $auth->assign($authorRole, $user->getId());
                if (Yii::$app->user->login($user, 3600*24*30)) {
                    Yii::$app->cache->flush();
                    $arMsg = [
                        'title' => 'Пользователь успешно зарегистрирован',
                        'css_class' => 'text-success',
                        'msg' => 'Поздравляем Вы успешно прошли регистрацию.',
                    ];
                } else {$arMsg['msg'] .= "Не удалось пройти авторизацию. Обратитесь к администратору.";}
            } else {$arMsg['msg'] .= "Не удалось активировать пользователя. Обратитесь к администратору.";}
        }
        else {$arMsg['msg'] .= "Не найден пользователь с ключом $auth_key";}

        return $this->render('confirm_registration', ['arMsg' => $arMsg]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->cache->flush();
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSubscribe() {
        $model = new SubscribeForm();
        if ($model->load(Yii::$app->request->post()) && $model->subscribe()) {
            return $this->render('subscribe_success');
        }
        $this->view->params['subscribeForm'] = $model;
        return $this->render('subscribe');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте ваш email для дальнейших инструкций.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Извините, нам не удалось сбросить пароль для указанного email-a. '. Html::errorSummary($model->user));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}