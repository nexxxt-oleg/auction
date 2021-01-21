<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\editable\Editable;
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\admin\models\search\GoodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цены';
$this->params['breadcrumbs'][] = $this->title;
\app\assets_b\GoodAdminAsset::register($this);
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>
<div class="good-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php Pjax::begin(['id' => 'goodIndex','enablePushState' => false,'timeout' => false,]); ?>
    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'value' => function ($data) {
                    return "<a href='". Yii::$app->urlManager->createUrl(['/good', 'id' => $data->id]) ."' >".$data->id."</a>";
                },
            ],
            [
                'attribute' => 'auction_id',
                'format' => 'raw',
                'value' => function ($data) {
                    /** @var \app\models\auction\Good $data */
                    if ($data->auction) {
                        return "{$data->auction->name} ($data->auction_id)";
                    } else {return $data->auction_id;}

                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name')
            ],
            'name',
            'description',
            [
                'class' => 'app\modules\admin\widgets\my_editable\MyEditableColumn',
                'attribute' => 'start_price',
                'editableOptions'=>[
                    'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                    'asPopover'=> false,
                    'inlineSettings' => [
                        'templateBefore' => Editable::INLINE_BEFORE_1,
                        'templateAfter' => Editable::INLINE_AFTER_1,
                    ],
                ],
                'format'=>['decimal', 2],
            ],
            [
                'class' => 'app\modules\admin\widgets\my_editable\MyEditableColumn',
                'attribute' => 'accept_price',
                'editableOptions'=>[
                    'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                    'asPopover'=> false,
                    'inlineSettings' => [
                        'templateBefore' => Editable::INLINE_BEFORE_1,
                        'templateAfter' => Editable::INLINE_AFTER_1,
                    ],
                ],
                'format'=>['decimal', 2],
            ],
            [
                'class' => 'app\modules\admin\widgets\my_editable\MyEditableColumn',
                'attribute' => 'mpc_price',
                'editableOptions'=>[
                    'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                    'asPopover'=> false,
                    'inlineSettings' => [
                        'templateBefore' => Editable::INLINE_BEFORE_1,
                        'templateAfter' => Editable::INLINE_AFTER_1,
                    ],
                ],
                'format'=>['decimal', 2],
            ],
            [
                'class' => 'app\modules\admin\widgets\my_editable\MyEditableColumn',
                'attribute' => 'blitz_price',
                'editableOptions'=>[
                    'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                    'asPopover'=> false,
                    'inlineSettings' => [
                        'templateBefore' => Editable::INLINE_BEFORE_1,
                        'templateAfter' => Editable::INLINE_AFTER_1,
                    ],
                ],
                'format'=>['decimal', 2],
            ],
            // 'start_price',
            // 'accept_price',
            // 'end_price',
            // 'curr_bid_id',
            // 'win_bid_id',
            // 'status',
            // 'type',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
