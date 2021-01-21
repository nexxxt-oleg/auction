<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\CommonHelper;
use app\modules\admin\models\search\GoodStat;

/* @var $this yii\web\View */
/* @var $model app\models\auction\Auction */
/* @var $userAllCount int */
/* @var $arUserAuction \app\models\auth\User[] */
/* @var $arViewFavorBid [] */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Auctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \app\modules\admin\widgets\AdminMenuWidget::widget();?>
<div class="auction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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
            'url:url',
            'active_date',
            'start_date',
            'end_date',
            'active',
        ],
    ]) ?>

    <div class="row">
        <h3>Всего пользователей зарегистрировано: <?= $userAllCount?></h3>
        <h3>Пользователей делало ставки в данном аукционе: <?= count($arUserAuction)?></h3>
        <p>
            <?php foreach($arUserAuction as $i => $user){
                if ($i > 0) {echo ", ";}
                echo "$user->name($user->id)";
            }?>
        </p>
        <h3>Лотов просмотрено/в избранном/сделано ставок: <?= "<a href='".Yii::$app->urlManager->createUrl(['/admingoodstat/viewed', CommonHelper::getShortClassName(GoodStat::className()) => ['auction_id' => $model->id]])."'>{$arViewFavorBid['viewedCount']}</a> /
            <a href='".Yii::$app->urlManager->createUrl(['/admingoodstat/favorite', CommonHelper::getShortClassName(GoodStat::className()) => ['auction_id' => $model->id]])."'>{$arViewFavorBid['favoriteCount']}</a> /
            <a href='".Yii::$app->urlManager->createUrl(['/adminbid/index', CommonHelper::getShortClassName(\app\modules\admin\models\search\GoodSearch::className()) => ['auction_id' => $model->id]])."'>{$arViewFavorBid['bidCount']}</a>"?>
        </h3>
    </div>

</div>
