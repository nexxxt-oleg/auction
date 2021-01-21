<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\admin\models\search\AuctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аукционы';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>

<div class="auction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать аукцион', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',
            'url:url',
            //'active_date',
            [
                'attribute' => 'start_date',
                'value' => function($data) {
                    /** @var $data \app\models\auction\Auction */
                    return Yii::$app->formatter->asDatetime($data->start_date, 'php:d.m.Y H:i:s');
                }
            ],
            [
                'attribute' => 'end_date',
                'value' => function($data) {
                    /** @var $data \app\models\auction\Auction */
                    return Yii::$app->formatter->asDatetime($data->end_date, 'php:d.m.Y H:i:s');
                }
            ],
            'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
