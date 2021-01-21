<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\admin\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Categories');
$this->params['breadcrumbs'][] = $this->title;
\app\assets_b\CategoryAdminAsset::register($this);
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>

<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>

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
            'priority',
            [
                'attribute' => 'auctions',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::activeDropDownList($data, 'auctions',
                        \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name'),
                        ['class' => 'au-list', 'data-id' => $data->id, 'multiple' => 'multiple']);
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\auction\Auction::find()->asArray()->all(), 'id', 'name')

            ],
            'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
