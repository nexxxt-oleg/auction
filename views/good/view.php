<?php

use app\assets_b\GoodViewAsset;use app\models\auction\Good;use kartik\widgets\Select2;use yii\helpers\ArrayHelper;use yii\helpers\FileHelper;
use yii\helpers\Html;use \yii\helpers\Url;
use \app\models\auction\Auction;use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/** @var  $model Good */
GoodViewAsset::register($this);
\kartik\select2\Select2Asset::register($this);
$this->title = $model->name;
$auLink = ['label' => 'Аукционы', 'url' => Url::previous()];
$auLink['url'] = ($auLink['url'] == "/" ?  Url::to(['good/index']) : $auLink['url']);
$bc[] = $auLink;
$bc[] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <?= Breadcrumbs::widget(['links' => $bc]);?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-7">

            <div id="sync1" class="owl-carousel lot-slider popup-gallery">
                <div class="item">
                <a class="item--magnifier" href="<?= $model->img_path?>" data-image-zoom="<?= $model->img_path?>" data-size="100">
                    <img src="<?= $model->img_path?>" alt="">
                </a>
                </div>
                <?php foreach($model->extra_img_paths as $file):?>
                    <div class="item">
                        <a class="item--magnifier" href="<?= $file?>" data-image-zoom="<?= $file?>" data-size="100">
                            <img src="<?= $file?>" alt="">
                        </a>
                    </div>
                <?php endforeach?>
            </div>

            <div class="thumbnail-wrap">
                <div id="sync2" class="owl-carousel thumbnail-slider">
                    <div class="item">
                        <img src="<?= $model->img_path?>" alt="">
                    </div>
                    <?php foreach($model->extra_img_paths as $file):?>
                    <div class="item">
                        <img src="<?= $file?>" alt="">
                    </div>
                    <?php endforeach?>
                </div>

                <div class="lot-navigation">
                    <span class="prev"><img src="/assets_b/img/icon/arrow-left.png" alt=""></span>
                    <span class="next"><img src="/assets_b/img/icon/arrow-right.png" alt=""></span>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-5">
            <div class="lot-content">
                <h2 class="lot-content__title"><?= $model->name?></h2>
                <?php if ($model->auction->active == Auction::ACTIVE_FLAG):?>
                    <time class="timer" datetime="<?= Yii::$app->formatter->asDate($model->auction->end_date, 'php:Y-m-d')."T".Yii::$app->formatter->asDate($model->auction->end_date, 'php:H:i:s')?>+0300"></time>
                <?php elseif ($model->auction->active == Auction::NEAREST_FLAG):?>
                    <!--<time class="timer" datetime="<?= Yii::$app->formatter->asDate($model->auction->start_date, 'php:Y-m-d\TH:i:s')?>"></time>-->
                <?php elseif ($model->auction->active == Auction::PAST_FLAG):?>
                    <?php if($model->win_bid_id):?>
                        <h5 class="lot-content__description-title">Аукцион завершен - продано</h5>
                    <?php else:?>
                        <h5 class="lot-content__description-title">Аукцион завершен - лот не продан</h5>
                    <?php endif?>
                <?php endif?>

                <div class="lot-content__form clearfix">
                    <div class="lot-content__price">
                        <div>
                        <?php if ($model->auction->active == Auction::ACTIVE_FLAG):?>
                            <?php if ($model->max_bid->user_id == Yii::$app->user->id):?>
                              <p>Ваша ставка является максимальной</p>
                            <?php elseif (in_array(Yii::$app->user->id, ArrayHelper::getColumn($model->bids, 'user_id'))):?>
                              <p>Ваша ставка не является максимальной</p>
                            <?php endif;?>
                        <?php elseif ($model->auction->active == Auction::PAST_FLAG):?>
                            <?php if ($model->win_bid->user_id == Yii::$app->user->id):?>
                                <p>Ваша ставка победила</p>
                            <?php else:?>
                                <p>Вашу ставку перебили</p>
                            <?php endif;?>
                        <?php endif;?>
                            <p id="price-name"><?= $model->win_bid_id ? 'Цена покупки' : ($model->max_bid ? 'Последняя ставка' : 'Стартовая цена')?>:</p>
                            <span id="curr_price"><?= $model->win_bid_id ? Yii::$app->formatter->asDecimal($model->win_bid->value) : Yii::$app->formatter->asDecimal($model->curr_price) ?></span>
                        </div>
                        <?php if ($model->mpc_price):?>
                        <div style="margin-top: 10px;">
                            <p id="price-name">МПЦ цена:</p>
                            <span><?= Yii::$app->formatter->asDecimal($model->mpc_price) ?></span>
                        </div>
                        <?php endif?>
                        <?php if ($model->blitz_price):?>
                        <div style="margin-top: 10px;">
                            <p id="price-name">Блитц цена:</p>
                            <span><?= Yii::$app->formatter->asDecimal($model->blitz_price) ?></span>
                        </div>
                        <?php endif?>
                        <div style="margin-top: 10px;">
                            <p id="price-name">Артикул: <?= $model->id ?></p>
                        </div>
                    </div>

                    <?php if($model->canDoBid()): ?>
                        <?php if(!$model->blitz_price || ($model->blitz_price && $model->curr_price < $model->blitz_price)):?>
                        <?= Html::hiddenInput('good_id', $model->id, ['id' => 'good_id'])?>
                        <?= Html::hiddenInput('step', $model->calculateStep(), ['id' => 'bid-step'])?>
                        <div class="offer-price">
                            <?= Html::dropDownList('bid_value', $model->getNextBidVal(), $model->getAvaibleBidVals(), [
                              'class' => '',
                              'id' => 'bid-value',
                            ])?>
                            <div class="btn-group-vertical" role="group" aria-label="...">
                              <button type="button" class="btn btn-default btn-xs" id="bid-up">
                                <span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
                              </button>
                              <button type="button" class="btn btn-default btn-xs" id="bid-down">
                                <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                              </button>
                            </div>
                            <button id="offer-price" class="lot-content__form-button">СДЕЛАТЬ СТАВКУ</button>
                        </div>
                        <?php endif?>

                    <?php endif?>
                </div>
                <?php if ($model->blitz_price && $model->auction->active == Auction::ACTIVE_FLAG):?>
                    <?php if($model->curr_price >= $model->blitz_price):?>
                    <div class="feedback__call" style="margin-top: 20px;">
                        <p>Предложена блитц цена</p>
                    </div>
                    <?php else:?>
                    <div class="feedback__call" style="margin-top: 20px;">
                        или предложите <button href="#blitz-modal" class="popup-modal">блитц-цену</button>
                    </div>
                    <?php endif?>
                <?php endif?>

                <?php if($model->canDoBid()): ?>

                <?php endif;?>

                <div class="lot-content__description">
                    <h5 class="lot-content__description-title">
                        описание:
                    </h5>
                    <p class="lot-content__description-text">
                        <?= Yii::$app->formatter->asNtext($model->description)?>
                    </p>
                </div>

                <div class="feedback">
                    <h6 class="feedback__title">Если вы хотите купить <span>подобный лот?</span></h6>
                    <div class="feedback__phone">
                        Позвоните нам <span><?= Yii::$app->params['phone']?></span>
                    </div>
                    <div class="feedback__call">
                        или закажите <button href="#call-modal" class="popup-modal">обратный звонок</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(in_array($model->id, [-1,-2,-3])):?>
    <div class="row">
        <div class="col-xs-12">
            <h2 class="lot-title">
                Подробенее о товаре
            </h2>
        </div>
        <div class="col-xs-12">
            <div class="lot-text clearfix">
                <img src="/assets_b/img/lot/1.jpg" alt="">
                <h3 class="lot-text__title">Клинок</h3>
                <p class="lot-text__text">Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес. Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвесПолицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес.Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес.Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес. Полицейский шты, период третьего рейха. Оригинальный</p>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="lot-text clearfix">
                <img src="/assets_b/img/lot/2.jpg" alt="">
                <h3 class="lot-text__title">Подвеска</h3>
                <p class="lot-text__text">Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес. Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвесПолицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес.Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес.Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес. Полицейский шты, период третьего рейха. Оригинальный</p>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="lot-text clearfix">
                <img src="/assets_b/img/lot/3.jpg" alt="">
                <h3 class="lot-text__title">Рукоятка</h3>
                <p class="lot-text__text">Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес. Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвесПолицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес.Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес.Полицейский шты, период третьего рейха. Оригинальный нож, накладки не переставлялись, покрытие родное. Эмблема фабричной установки, оригинальная, без вмешательств. Производитель Lüneschloss. В комплекте оригинальный темляк и подвес. Полицейский шты, период третьего рейха. Оригинальный</p>
            </div>
        </div>
    </div>
    <?php endif?>