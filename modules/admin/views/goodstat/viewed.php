<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\admin\models\search\GoodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика просмотров';
$this->params['breadcrumbs'][] = $this->title;
\app\assets_b\GoodAdminAsset::register($this);
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>

<div class="good-index">

    <h1><?= Html::encode($this->title) ?></h1>


<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'auction_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return "{$data->auction->name} ({$data->auction_id})";
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name')

            ],

            'cnt_viewed',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    /** @var \yii\db\ActiveRecord $model */
                    if ($action === 'view') {
                        $url = Yii::$app->urlManager->createUrl(['/admingood/view', 'id' => $model->id]);
                    } else {
                        $url = \yii\helpers\Url::toRoute([$action, 'id' => $model['ID']]);
                    }
                    return $url;
                }
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
