<?php

use app\models\ContactForm;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */
/* @var $model ContactForm */

?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'form form--contacts'],
    'action' => Yii::$app->urlManager->createUrl('site/contacts'),
    'fieldConfig' => [
        'options' => ['class' => 'form__group'],
        'inputOptions' => ['class' => 'form__input'],
        'labelOptions' => ['class' => 'form__label'],
    ],

]);?>
<?= Html::activeInput('hidden', $model, 'type', ['id' => "$model->type-hidden"]);?>
<h5 class="form__title"><?= $model->type ? array_search($model->type, $model->getTypes()) : array_search(ContactForm::TYPE_COMMON, $model->getTypes())?></h5>
<div class="row">
    <div class="col-xs-12 col-md-5">
        <?= $form->field($model, 'name')->textInput(['id' => "$model->type-name"]);?>
        <?= $form->field($model, 'phone')->textInput(['id' => "$model->type-phone"]);?>
        <?= $form->field($model, 'email')->textInput(['id' => "$model->type-email"]);?>
    </div>
    <div class="col-xs-12 col-md-7">
        <?= $form->field($model, 'body')->textarea([
            'class' => 'form__textarea',
            'id' => "$model->type-body",
        ])->label($model->printBodyLabel());?>
    </div>
    <div class="row">
        <div class="coll-xs-12 col-md-5">
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-md-6">{image}</div><div class="col-md-6">{input}</div></div>',
                'options' => ['class' => 'form__input', 'id' => "$model->type-verifyCode",],
            ]) ?>
        </div>
        <div class="col-xs-12 col-md-7">
            <?= \yii\bootstrap\Html::submitButton('Отправить', ['class' => 'default-button default-button--contacts'])?>
        </div>
    </div>

</div>
<?php ActiveForm::end();?>