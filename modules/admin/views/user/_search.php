<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\admin\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'login') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'auction_name') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'add_time') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
