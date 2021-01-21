<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $goodSearch \app\models\auction\GoodSearch */

$this->title = 'Аукционы';
\app\assets_b\GoodIndexAsset::register($this);
?>
<div class="row">
<div class="col-xs-12 col-sm-4 col-md-3">
    <div class="auction-sort">
        <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'form-filter',
            'class' => 'form',
            'options' => ['class' => 'form-horizontal'],

        ]);?>
        <?= $goodSearch->renderLeftForm($form);?>

        <?php \yii\bootstrap\ActiveForm::end(); ?>
    </div>
</div>


<div class="col-xs-12 col-sm-8 col-md-9">
    <div class="row">
        <div class="auction-list clearfix">
            <?= $this->render('_index', ['dataProvider' => $dataProvider]);?>
        </div>
    </div>
</div>
</div>
