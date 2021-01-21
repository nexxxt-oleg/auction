<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Auction */

$this->title = 'Изменить аукцион: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Auctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>
<div class="auction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
