<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\auction\Good;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Good */
/* @var $searchModel \app\modules\admin\models\search\BidSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Good');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Все лоты', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'auction_id',
                'value' => "{$model->auction->name} ({$model->auction_id})",
            ],
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
            [
                'attribute' => 'add_time',
                'value' => $model->add_time ? Yii::$app->formatter->asDate($model->add_time, 'php:d.m.Y H:i:s') : 'Не задано'
            ],
        ],
    ]) ?>

    <div class="row">
        <div class="col-xs-12 col-md-7">

            <div id="sync1" class="owl-carousel lot-slider popup-gallery">
                <div class="item">
                    <img src="<?= $model->img_path?>" alt="">
                </div>
                <?php foreach($model->extra_img_paths as $file):?>
                    <div class="item">
                        <a class="item--magnifier" href="<?= $file?>" data-image-zoom="<?= $file?>" data-size="100">
                            <img src="<?= $file?>" alt="">
                        </a>
                    </div>
                <?php endforeach?>
            </div>

            <div class="thumbnail-wrap">
                <div id="sync2" class="owl-carousel thumbnail-slider">
                    <div class="item">
                        <img src="<?= $model->img_path?>" alt="">
                    </div>
                    <?php foreach($model->extra_img_paths as $file):?>
                        <div class="item">
                            <img src="<?= $file?>" alt="">
                        </div>
                    <?php endforeach?>
                </div>

                <div class="lot-navigation">
                    <span class="prev"><img src="/assets_b/img/icon/arrow-left.png" alt=""></span>
                    <span class="next"><img src="/assets_b/img/icon/arrow-right.png" alt=""></span>
                </div>
            </div>
        </div>
    </div>


    <?php
    if($dataProvider->totalCount > 0) {
        echo \yii\grid\GridView::widget([
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
        ]);
    }
     ?>

    <?php if($model->win_bid):?>
        <h2>Победитель - <?= $model->max_bid_user?></h2>
        <p>Телефон: <?= $model->win_bid->user->phone?></p>
        <p>Email: <?= $model->win_bid->user->email?></p>
    <?php endif?>

</div>
