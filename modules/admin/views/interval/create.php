<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \app\modules\admin\models\RobotInterval */

$this->title = 'Создание интервала';
$this->params['breadcrumbs'][] = ['label' => 'Robot Intervals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="robot-interval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
