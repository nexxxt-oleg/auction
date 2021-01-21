<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 13:31
 */
/* @var $type string */
/** @var $cart \app\components\shop\MyShoppingCart */
?>

<ul <?= ($type == 'index'
    ? 'class="action-button wow fadeInDown" data-wow-duration="1s" data-wow-delay="1s" style="visibility: visible; animation-duration: 1s; animation-delay: 1s; animation-name: fadeInDown;"'
    : 'class="action-button action-button--green"')?>>
    <?php if(!Yii::$app->user->isGuest):?>
    <li class="action-button__item">
        <a class="action-button__link action-button__link--basket" href="<?= Yii::$app->urlManager->createUrl('/cabinet#s-basket')?>">
            <img src="/assets_b/img/icon/basket<?= $type == 'index' ? '' : '2'?>.svg" alt="">
            <span><?= $cart->getCount()?></span>
        </a>
    </li>
    <!-- измени модификатор на action-button__link--star-true при добавлении товара в избранное -->
    <li class="action-button__item action-button__link--star <?= ($cart->getCountFavorite() > 0) ? 'action-button__link--star-true' : ''?>">
        <a class="action-button__link" href="<?= Yii::$app->urlManager->createUrl('/cabinet#s-liked')?>">
            <img src="/assets_b/img/icon/star<?= $type == 'index' ? '' : '2'?>.svg" alt="">
            <span><?= $cart->getCountFavorite()?></span>
        </a>
    </li>
    <?php endif?>
    <?php if($cart->getCountViewed() > 0):?>
    <li class="action-button__item">
        <a class="action-button__link action-button__link--view" href="<?= Yii::$app->urlManager->createUrl('/cabinet#s-viewed')?>">
            <img src="/assets_b/img/icon/eye<?= $type == 'index' ? '' : '2'?>.svg" alt="">
            <span><?= $cart->getCountViewed()?></span>
        </a>
    </li>
    <?php endif?>
</ul>