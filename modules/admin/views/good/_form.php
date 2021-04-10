<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use app\models\auction\Good;

/* @var $this yii\web\View */
/* @var $model Good */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="good-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]);
    $qwe =\kartik\widgets\FileInput::classname();
    //echo $qwe;
    ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'auction_id')->dropDownList(ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->orderBy('start_date DESC')->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\app\models\auction\Category::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'start_price')->textInput() ?>

    <?= $form->field($model, 'step')->textInput() ?>

    <?= $form->field($model, 'accept_price')->textInput() ?>

    <?= $form->field($model, 'mpc_price')->textInput() ?>

    <?= $form->field($model, 'blitz_price')->textInput() ?>

    <?php //echo $form->field($model, 'end_price')->textInput() ?>

    <?php //echo $form->field($model, 'curr_bid_id')->textInput() ?>

    <?php //echo $form->field($model, 'win_bid_id')->textInput() ?>

    <?= $form->field($model, 'sell_rule')->dropDownList(Good::arSellRule())?>

    <?= $form->field($model, 'status')->dropDownList(Good::arStatus()) ?>

    <?php $model->type = $model->isNewRecord ? Good::TYPE_COMMON : $model->type;
    echo $form->field($model, 'type')->dropDownList(Good::arType()) ?>

    <?php
    echo $form->field($model, 'mainImage')->widget(\kartik\widgets\FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => [
            'allowedFileExtensions'=>['jpg','gif','png'],
            'showUpload' => false,
        ]
    ]);?>

    <?php
    echo $form->field($model, 'extraImages[]')->widget(\kartik\widgets\FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowedFileExtensions'=>['jpg','gif','png'],
            'showUpload' => false,
        ]
    ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton( 'Создать новый лот', ['class' => 'btn btn-success', 'name' => 'create_another']) ?>

        <?= Html::a('Все лоты', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
