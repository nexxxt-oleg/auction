<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\search\GoodSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="good-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'auction_id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?php // echo $form->field($model, 'start_price') ?>

    <?php // echo $form->field($model, 'accept_price') ?>

    <?php // echo $form->field($model, 'end_price') ?>

    <?php // echo $form->field($model, 'curr_bid_id') ?>

    <?php // echo $form->field($model, 'win_bid_id') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
