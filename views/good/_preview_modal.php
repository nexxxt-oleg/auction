<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 27.07.2016
 * Time: 11:19
 */

use \app\models\auction\Auction;
/* @var $model \app\models\auction\Good */
?>

    <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
    <div class="row">
        <div class="col-xs-12 col-md-7">

            <div id="sync1" class="owl-carousel lot-slider popup-gallery">
                    <div class="item">
                        <a class="item--magnifier" href="#" data-image-zoom="<?= $model->img_path?>" data-size="100">
                            <img src="<?= $model->img_path?>" alt="">
                        </a>
                    </div>
                    <?php foreach($model->extra_img_paths as $file):?>
                        <div class="item">
                            <a class="item--magnifier" href="#" data-image-zoom="<?= $file?>" data-size="100">
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
            <div class="lot-content lot-content--popup">
                <h2 class="lot-content__title"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/good', 'id' => $model->id]) ?>"><?= $model->name ?></a></h2>
                <time class="timer" datetime="<?= Yii::$app->formatter->asDate($model->auction->start_date, 'php:Y-m-d')."T".Yii::$app->formatter->asDate($model->auction->start_date, 'php:H:i:s')?>+0300"></time>

                <form action="#" class="lot-content__form clearfix">
                    <div class="lot-content__price">
                        <?php if ($model->auction->active == Auction::PAST_FLAG):?>
                            <?php if($model->win_bid_id):?>
                                <p>Аукцион завершен - продано</p>
                            <?php else:?>
                                <p>Аукцион завершен - лот не продан</p>
                            <?php endif?>
                        <?php endif?>
                    </div>
                    <div class="lot-content__price">

                         <p><?= $model->win_bid_id ? 'Цена покупки' : ($model->max_bid ? 'Последняя ставка' : 'Стартовая цена')?>:</p>
                        <span id="curr_price"><?= $model->win_bid_id ? Yii::$app->formatter->asDecimal($model->win_bid->value) : Yii::$app->formatter->asDecimal($model->curr_price) ?></span>
                    </div>
                    <?php if($model->canDoBid() && false ): // todo: !!!!!!?>
                    <div class="form-group">
                        <input class="lot-content__form-input" type="text" placeholder="Введите сумму ставки">
                        <?= \yii\helpers\Html::hiddenInput('good_id', $model->id, ['id' => 'good_id'])?>
                        <button  type="button" class="lot-content__form-button">СДЕЛАТЬ СТАВКУ</button>
                    </div>
                    <?php endif?>
                </form>

                <div class="feedback feedback--popup">
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
    <div class="clearfix"></div>
