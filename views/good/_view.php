<?php

use yii\helpers\Html;
use \app\models\auction\GoodFavorite;

/* @var $this yii\web\View */
/* @var $model \app\models\auction\GoodSearch */
/** @var \app\components\shop\MyShoppingCart $cart */

?>

<div class="auction-item">
    <h3 class="auction-item__title">
        <a href="<?= Yii::$app->urlManager->createUrl(['/good', 'id' => $model->id]) ?>"><?= $model->name ?> (Лот №<?= $model->id ?>)</a>
    </h3>

    <div class="auction-item__img">

        <img src="<?= $model->img_path?>" alt="<?= $model->name?>">
            <span class="auction-item__img-mask">
                <span>
                    <?php if($model->canDoBid()): ?>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/good', 'id' => $model->id]) ?>">Сделать ставку</a>
                    <?php endif?>
                    <a href="#preview-modal" class="popup-modal-item" data-id="<?= $model->id?>">Быстрый просмотр</a>
                </span>
            </span>
    </div>
    <div class="auction-item__status">
        <?php if ($model->auction->active == \app\models\auction\Auction::ACTIVE_FLAG):?>
        <span class="auction-item__countdown"><img src="/assets_b/img/icon/timer.svg" alt="">До конца торгов <time
            class="timer"
            datetime="<?= Yii::$app->formatter->asDate($model->auction->end_date, 'php:Y-m-d')."T".Yii::$app->formatter->asDate($model->auction->end_date, 'php:H:i:s')?>+0300"></time></span>
        <?php elseif ($model->auction->active == \app\models\auction\Auction::NEAREST_FLAG):?>
           <span class="auction-item__starting"><img src="/assets_b/img/icon/calendar.svg" alt="">Начало торгов:
                <?= Yii::$app->formatter->asDate($model->auction->start_date, 'php:d.m.Y H:i') ?></span>
        <?php else:?>
            <span class="auction-item__starting"><img src="/assets_b/img/icon/sales.png" alt="">Аукцион завершен - <?= $model->win_bid_id ? '<span class="auction-item__sales">ПРОДАНО</span>' : 'лот не продан'?></span>
        <?php endif?>
    </div>
    <div class="auction-item__price">
        <? $this->registerCss(".auction-item__price .price span:after { content: '{$model->auction->currency}'; }"); ?>
        <?php if ($model->auction->active == \app\models\auction\Auction::ACTIVE_FLAG):?>
            <span class="price price--start">
            <p>Стартовая цена:</p>
            <span><?= $model->start_price ?></span>
        </span>
            <span class="price price--current">
            <p><?= $model->win_bid_id ? 'Цена покупки' : 'Текущая цена:' ?></p>
            <span><?= $model->win_bid_id ? Yii::$app->formatter->asDecimal($model->win_bid->value) : Yii::$app->formatter->asDecimal($model->curr_price) ?></span>
        </span>
        <?php elseif ($model->auction->active == \app\models\auction\Auction::NEAREST_FLAG):?>
            <span class="price price--start">
                <p>Стартовая цена:</p>
                <span><?= Yii::$app->formatter->asDecimal($model->start_price) ?></span>
            </span>
        <?php else:?>
            <span class="price price--start">
                <p>Стартовая цена:</p>
                <span><?= Yii::$app->formatter->asDecimal($model->start_price) ?></span>
            </span>
            <?php if ($model->win_bid_id):?>
                <span class="price price--current">
                    <p>Цена продажи:</p>
                    <span><?= Yii::$app->formatter->asDecimal($model->win_bid->value)?></span>
                </span>
            <?php elseif ($model->max_bid):?>
            <span class="price price--current">
                <p>Последняя ставка:</p>
                <span><?= Yii::$app->formatter->asDecimal($model->max_bid->value)?></span>
            </span>
            <?php endif?>
        <?php endif?>



        <ul class="action">
            <?php if(!Yii::$app->user->isGuest):?>
            <li class="to-favorite" <?= ($cart->hasPositionFavorite($model->getId())) ? '' : 'style="opacity: 0.5"'?>>
                <a href="#" data-good-id="<?= $model->id?>" data-action="<?= ($cart->hasPositionFavorite($model->getId())) ? GoodFavorite::ACTION_REMOVE : GoodFavorite::ACTION_ADD?>"><img src="/assets_b/img/icon/item-star.svg" alt=""></a>
            </li>
            <?php endif?>
            <?php if ($cart->hasPositionViewed($model->getId())):?>
            <li><img src="/assets_b/img/icon/item-watch.svg" alt=""></li>
            <?php endif?>
        </ul>
    </div>
</div>
