<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */
/** @var  $model \app\models\SubscribeForm */
$this->title = 'Подписка';
$model = isset($this->params['subscribeForm']) ? $this->params['subscribeForm'] : new \app\models\SubscribeForm();
$bc[] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <?= \yii\widgets\Breadcrumbs::widget(['links' => $bc]);?>
    </div>
    <div class="col-xs-12">
        <h2 class="content-title"><?=$this->title?></h2>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-4">
        <?php
        $form = \yii\bootstrap\ActiveForm::begin([
            //'options' => ['class' => 'form__contacts'],
            'action' => Yii::$app->urlManager->createUrl('site/subscribe'),
        ]); ?>
        <?= $form->field($model, 'email', [
            'options' => ['class' => 'form__group'],
            'inputOptions' => ['class' => 'form__input', 'placeholder' => 'Email'],
            'labelOptions' => ['class' => 'form__label'],
        ]); ?>
        <?= Html::submitButton('ПОДПИСАТЬСЯ', ['class' => 'default-button default-button--contacts']); ?>
        <?php \yii\bootstrap\ActiveForm::end();
        ?>
    </div>
</div>
<br>
