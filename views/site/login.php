<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <h5 class="basic-modal__title">вход</h5>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Введите пароль и логин:</h6>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'class' => 'form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                'labelOptions' => ['class' => 'form__label'],
                'inputOptions' => ['class' => 'form__input'],
            ],
        ]); ?>
        <?= $form->field($model, 'username', [
            'template' => "{label}\n{input}\n{hint}\n{error}",
            'options' => ['class' => 'form__group']
        ]) ?>
        <?= $form->field($model, 'password', [
            'template' => "{label}\n{input}\n{hint}\n{error}",
            'options' => ['class' => 'form__group']
        ])->passwordInput() ?>
            <?= Html::submitButton('вход', ['class' => 'default-button default-button--login', 'name' => 'login-button']) ?>
            <?= Html::a('Забыли пароль?', ['request-password-reset',], ['class' => 'default-button default-button--login']) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <!--<div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Войти через социальные сети:</h6>
        <p class="basic-modal__text">Вы можете войти, используя свою учётную запись в социальных сетях.</p>

        <ul class="basic-modal__social">
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/vk.svg" alt="">Вконтакте</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/facebook.svg" alt="">Facebook</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/twitter.svg" alt="">Twitter</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/odnoc.svg" alt="">Однокласники</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/googleplus.svg" alt="">Google</a>
            </li>
        </ul>
    </div>-->
</div>
<?php /*
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Нет аккаунта? <?= Html::a('Зарегистрироваться', ['signup'])?> </p>
    <p>Заполните, пожалуйста, следующие поля:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username') ?>


        <?= $form->field($model, 'password')->passwordInput() ?>

        <?php //echo $form->field($model, 'rememberMe')->checkbox([
            //'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        //]) ?>

        <div class="form-group">
            <div class="col-lg-12">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
*/?>