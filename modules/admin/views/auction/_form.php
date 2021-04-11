<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Auction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATETIME,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
        ],

    ]) ?>

    <?= $form->field($model, 'end_date')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATETIME,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
        ],
    ]) ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_test')->dropDownList([false => 'Нормальный', true => 'Тестовый (невидимый)']) ?>

    <?php $model->active = $model->isNewRecord ? \app\models\auction\Auction::DISABLE_FLAG : $model->active;
    echo $form->field($model, 'active')->dropDownList(\app\models\auction\Auction::getArActive()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
