<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 13:31
 */
/* @var $type string */
/* @var $arCatModel array */
/** @var  $auFlag string */
use \app\models\auction\Auction;
?>
<?php $clearUrl = "/".Yii::$app->request->getPathInfo()
    .((isset($_REQUEST['GoodSearch']['top_menu'])) ? "?".urlencode("GoodSearch[top_menu]")."=".$_REQUEST['GoodSearch']['top_menu'] : '')
    .((isset($_REQUEST['GoodSearch']['next_flag'])) ? "&".urlencode("GoodSearch[next_flag]")."=".$_REQUEST['GoodSearch']['next_flag'] : '');?>
<?= \yii\helpers\Html::tag('span', $clearUrl, ['id' => 'clearUrl', 'style' => ['display' => 'none']]);?>
<div class="navigation-mobile-wrap <?= $type == 'index' ? 'wow fadeInRight' :''?>" <?= $type == 'index' ? 'data-wow-duration="1s"' :''?>>

    <button type="button" class="navigation-mobile-button <?= $type != 'index' ? 'navigation-mobile-button--green' :''?>">
        <img class="navigation-mobile-button__img navigation-mobile-button__img--burger" src="/assets_b/img/icon/burger<?= $type != 'index' ? '2' :''?>.svg" alt="">
        <img class="navigation-mobile-button__img navigation-mobile-button__img--close" src="/assets_b/img/icon/close2.svg" alt="">
    </button>


    <?php $arNextParams = ['/good/index', 'GoodSearch[top_menu]' => "next", 'GoodSearch[next_flag]' => $auFlag];?>
    <ul class="navigation-mobile" id="navigation-mobile">

        <li class="navigation-mobile__top header__col-right">
            <div class="d-flex">
                <div class="header__phone">
                    <img src="/assets_b/img/icon/ico-phone2.svg" alt=""> <?= Yii::$app->params['phone']?>
                </div>
                <?php if (Yii::$app->getUser()->isGuest):?>
                    <button href="#login-modal" class="user-button__login popup-modal">
                        <img src="/assets_b/img/icon/ico-user2.svg" alt="">
                        <span>Вход</span>
                    </button>
                    <button href="#registration-modal" class="user-button__signup popup-modal">
                        <img src="/assets_b/img/icon/ico-enter2.svg" alt="">
                        <span>Регистрация</span>
                    </button>
                <?php else:?>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/cabinet#s-settings'])?>" class="user-button__login">
                        <img src="/assets_b/img/icon/ico-user2.svg" alt="">
                        <span><?= Yii::$app->user->identity->name?></span>
                    </a>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/site/logout'])?>" class="user-button__signup">
                        <img src="/assets_b/img/icon/ico-enter2.svg" alt="">
                        <span>Выход</span>
                    </a>

                <?php endif?>
            </div>
            <div class="navigation-mobile__logo">
                <a class="logo__img" href="/">
                    <img src="/assets_b/img/logo.svg" alt="">
                </a>

            </div>
        </li>

        <li class="navigation-mobile__item <?= ($clearUrl == Yii::$app->urlManager->createUrl($arNextParams)) ? 'navigation-mobile__item--active' : ''?>">
            <a href="<?= Yii::$app->urlManager->createUrl($arNextParams)?>" class="navigation-mobile__link">
                <?php
                switch($auFlag){
                    case Auction::NEAREST_FLAG:
                    default:
                        echo 'Ближайший аукцион';
                        break;
                    case Auction::ACTIVE_FLAG:
                        echo 'Текущий аукцион';
                        break;
                    case Auction::PAST_FLAG:
                        echo 'Прошедший аукцион';
                        break;
                }
                ?>
            </a>
        </li>
        <li class="navigation-mobile__item <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "all"])) ? 'navigation-mobile__item--active' : ''?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "all"])?>" class="navigation-mobile__link">Все лоты</a>
        </li>
        <?php /* @var $category \app\models\auction\Category */
        foreach($arCatModel as $category):?>
            <li class="navigation-mobile__item <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "category$category->id"])) ? 'navigation-mobile__item--active' : ''?>">
                <a href="<?= Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "category$category->id"])?>" class="navigation-mobile__link">
                    <?= $category->name?>
                </a>
            </li>

        <?php endforeach?>
        <li class="navigation-mobile__item <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "category$category->id"])) ? 'navigation-mobile__item--active' : ''?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['/site/faq'])?>" class="navigation-mobile__link">Узнайте подробнее</a>
        </li>
        <li class="navigation-mobile__item <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "category$category->id"])) ? 'navigation-mobile__item--active' : ''?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['/site/contacts'])?>" class="navigation-mobile__link">
                Контакты
            </a>
        </li>
        <li class="navigation-mobile__email">
            <a href="mailto:<?= Yii::$app->params['adminEmail']?>"><?= Yii::$app->params['adminEmail']?></a>
            По вопросам проведения аукционов:
        </li>
        <? /*
        <li class="user-button user-button--mobile">
            <?php if (Yii::$app->getUser()->isGuest): ?>
                <button href="#login-modal" class="user-button__login popup-modal">
                    <img src="/assets_b/img/icon/login.svg" alt="">
                </button>
                <button href="#registration-modal" class="user-button__signup popup-modal">
                    <img src="/assets_b/img/icon/signup.svg" alt="">
                </button>
            <?php else: ?>
                <a href="<?= Yii::$app->urlManager->createUrl(['/cabinet#s-settings']) ?>" class="user-button__login">
                  <img src="/assets_b/img/icon/signup.svg" alt="">
                </a>
                <a href="<?= Yii::$app->urlManager->createUrl(['/site/logout']) ?>" class="user-button__signup">
                  <img src="/assets_b/img/icon/login.svg" alt="">
                </a>
            <?php endif;?>
            <button class="user-button__search">
                <img src="/assets_b/img/icon/search.svg" alt="">
            </button>
        </li>
 */ ?>
    </ul>
</div>