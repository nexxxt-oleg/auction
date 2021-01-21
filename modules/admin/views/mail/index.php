<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\search\MailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Почта';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>

<div class="mail-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'user_name',
            'subject',
            'body:ntext',
            'date',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function($data) {
                   return \app\models\Mail::printType($data->type);
                },
                'filter'=>\app\models\Mail::arType(),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
