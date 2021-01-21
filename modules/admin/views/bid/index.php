<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\auction\Good;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\admin\models\search\GoodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ставки';
$this->params['breadcrumbs'][] = $this->title;
\app\assets_b\BidAdminAsset::register($this);
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>

<div class="bid-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'auction_id',
                'format' => 'raw',
                'value' => function ($data) {
                    /** @var Good $data */
                    if ($data->auction) {
                        return "{$data->auction->name} ($data->auction_id)";
                    } else {$data->auction_id;}

                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name')
            ],
            'name',
            [
                'attribute' => 'sell_rule',
                'value' => function($data) {
                    /** @var $data \app\modules\admin\models\search\GoodSearch */
                    return Good::printSellRule($data->sell_rule);
                }
            ],
            'start_price',
            'accept_price',
            'bid_count',
            [
                'attribute' => 'max_bid',
                'value' => function($data) {
                    /** @var $data \app\modules\admin\models\search\GoodSearch */
                    return $data->max_bid->value;
                }
            ],
            'max_bid_user',
            [
                'attribute' => 'max_bid_date',
                'value' => function($data) {
                    /** @var $data \app\modules\admin\models\search\GoodSearch */
                    return Yii::$app->formatter->asDatetime($data->max_bid->date, 'php:d.m.Y H:i:s');
                }
            ],

            // 'end_price',
            // 'curr_bid_id',
            // 'win_bid_id',
            // 'status',
            // 'type',


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url = Yii::$app->urlManager->createUrl(['/admingood/view', 'id' => $model['ID']]);
                    } else {
                        $url = \yii\helpers\Url::toRoute([$action, 'id' => $model['ID']]);
                    }
                    return $url;
                }
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
