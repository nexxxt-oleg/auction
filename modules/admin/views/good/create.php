<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\auction\Good */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Good'),]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
