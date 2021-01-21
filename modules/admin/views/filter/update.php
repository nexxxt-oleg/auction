<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Filter */

$this->title = Yii::t('app', 'Update {modelClass}', ['modelClass' => Yii::t('app', 'Filter'),]) . ": $model->name";
$this->params['breadcrumbs'][] = ['label' => 'Filters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="filter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
