<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\auth\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
\app\assets_b\UserAdminAsset::register($this);
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'login',
            [
                'attribute' => 'password',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::button('Сбросить',['class' => 'btn btn-default btn-xs reset-password', 'data-user-id' => $data->id]);
                },
            ],
            //'auction_name',
            'email:email',
            'name',
            'phone',
            // 'info',
            [
                'attribute' => 'active',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::activeDropDownList($data, 'active', User::arActive(),
                        ['class' => 'active-status', 'data-user-id' => $data->id]);
                },
                'filter'=>User::arActive(),
            ],
            // 'add_time',
            // 'password_reset_token',
            // 'auth_key',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
