<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\auction\Auction */

$this->title = 'Создать аукцион';
$this->params['breadcrumbs'][] = ['label' => 'Auctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>
<div class="auction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
