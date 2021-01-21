<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */
/* @var $model \app\models\SubscribeForm */

?>
<div class="subscribe">
    <div class="subscribe__text">
        ПОДПИШИСЬ НА НОВОСТИ И <span>ПОЛУЧАЙ АКТУАЛЬНУЮ ИНФОРМАЦИЮ</span>  О НОВЫХ АУКЦИОНАХ
    </div>
<?php \yii\bootstrap\ActiveForm::begin([
    'options' => ['class' => 'subscribe__form'],
    'action' => Yii::$app->urlManager->createUrl('site/subscribe'),
]);?>
<?= Html::activeInput('text', $model, 'email', ['class' => 'subscribe__input', 'placeholder' => 'Email']);?>
<?= Html::submitButton('ПОДПИСАТЬСЯ', ['class' => 'subscribe__button']);?>
<?php \yii\bootstrap\ActiveForm::end();?>
</div>
