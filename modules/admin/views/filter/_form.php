<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use app\models\auction\Filter;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Filter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="filter-form">

    <?php $form = ActiveForm::begin(); ?>
 <?= $form->errorSummary($model); ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\app\models\auction\Category::find()->asArray()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level')->dropDownList(Filter::getArLevel()) ?>

    <?= $form->field($model, 'parent')->dropDownList(Filter::getArParent()/*ArrayHelper::map(Filter::find()->where(['level' => 1])->asArray()->orderBy('category_id')->all(), 'id', 'name')*/) ?>


    <?= $form->field($model, 'active')->dropDownList(Filter::getArActive()) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
