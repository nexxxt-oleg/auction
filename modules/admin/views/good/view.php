<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\auction\Good;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Good */
/* @var $searchModel \app\modules\admin\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>
<div class="good-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить данную запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
            [
                'attribute' => 'auction_id',
                'value' => "{$model->auction->name} ({$model->auction_id})",
            ],
            [
                'attribute' => 'category_id',
                'value' => "{$model->category->name} ({$model->category_id})",
            ],
            'start_price',
            'accept_price',
            'end_price',
            'curr_bid_id',
            'win_bid_id',
            [
                'attribute' => 'status',
                'value' => Good::printStatus($model->status),
            ],
            [
                'attribute' => 'type',
                'value' => Good::printType($model->type),
            ],
        ],
    ]) ?>


    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'value',
            [
                'attribute' => 'good_id',
                'format' => 'raw',
                'value' => function ($data) {
                    /** @var \app\models\auction\Bid $data */
                    return "{$data->good->name} ({$data->good_id})";
                },
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    /** @var \app\models\auction\Bid $data */
                    return "{$data->user->name} ({$data->user_id})";
                },
            ],
            'date',


            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php if($model->win_bid):?>
        <h2>Победитель - <?= $model->max_bid_user?></h2>
        <p>Телефон: <?= $model->win_bid->user->phone?></p>
        <p>Email: <?= $model->win_bid->user->email?></p>
    <?php endif?>

</div>
