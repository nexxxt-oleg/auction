<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\admin\models\search\GoodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Лоты';
$this->params['breadcrumbs'][] = $this->title;
\app\assets_b\GoodAdminAsset::register($this);
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>
<div class="good-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>

    </p>

<?php Pjax::begin(); ?>    <?= GridView::widget([
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
            'name',
            [
                'attribute' => 'auction_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::activeDropDownList($data, 'auction_id',
                        \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name'),
                        ['class' => 'au-list', 'data-good-id' => $data->id]);
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name')

            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::activeDropDownList($data, 'category_id',
                        \yii\helpers\ArrayHelper::map(\app\models\auction\Category::find()->asArray()->all(), 'id', 'name'),
                        ['class' => 'cat-list', 'data-good-id' => $data->id]);
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Category::find()->asArray()->all(), 'id', 'name')

            ],
            [
                'attribute' => 'filters',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::activeDropDownList($data, 'filters',
                        \app\models\auction\Filter::getArAll(),
                        ['class' => 'filter-list', 'data-good-id' => $data->id, 'multiple' => 'multiple']);
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Filter::find()->where(['level' => \app\models\auction\Filter::LEVEL_CHILD])->asArray()->all(), 'id', 'name')

            ],
            [
                'attribute' => 'sell_rule',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::activeDropDownList($data, 'sell_rule', \app\models\auction\Good::arSellRule(),
                    ['class' => 'sell-rule', 'data-good-id' => $data->id]);
                },
                'filter' => \app\models\auction\Good::arSellRule()
            ],
            // 'start_price',
            // 'accept_price',
            // 'end_price',
            // 'curr_bid_id',
            // 'win_bid_id',
            // 'status',
            // 'type',
            [
                'attribute' => 'is_blitz_reached',
                'format' => 'raw',
                'filter' => [false => 'Нет', true => 'Да']
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {view_as_user} {update} {delete}',
                'buttons'=>[
                    'view_as_user'=>function ($url, $model) {
                        $customurl=Yii::$app->getUrlManager()->createUrl(['good','id'=>$model['id']]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-close"></span>', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    }
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
