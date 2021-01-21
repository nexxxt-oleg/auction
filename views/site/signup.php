<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\auth\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<?php $form = ActiveForm::begin([
    'id' => 'form-signup',
    'action' => Yii::$app->urlManager->createUrl(['/site/signup']),
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'form__label'],
        'inputOptions' => ['class' => 'form__input'],
    ],
]);
?>

    <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
    <div class="col-xs-12">
        <h5 class="basic-modal__title">Регистрация</h5>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Заполните пожалуйста поля!</h6>


        <?= $form->field($signUpModel, 'fio', [ 'options' => ['class' => 'form__group']]) ?>
        <?= $form->field($signUpModel, 'phone', [ 'options' => ['class' => 'form__group']]) ?>
        <?= $form->field($signUpModel, 'email', [ 'options' => ['class' => 'form__group']]) ?>



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
    <div class="col-xs-12">
        <?= Html::submitButton('начать покупки', ['class' => 'default-button', 'name' => 'signup-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>
