<?php
/* @var $this \yii\web\View */
/* @var $loginModel \app\models\auth\LoginForm */
/* @var $arCatModel array */

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use \app\models\auction\Auction;
use \app\models\ContactForm;
use \app\models\auction\Good;
use \app\components\CommonHelper;

\app\assets_b\IndexAsset::register($this);
/** @var Good[] $indexGoods */
$indexGoods = Good::find()->where(['type' => Good::TYPE_INDEX])->limit(2)->all();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta charset="<?= Yii::$app->charset ?>">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>

    <!-- Font -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <?php $this->head() ?>
</head>
<body class="promo-page">
<?php $this->beginBody() ?>
<?
if (Yii::$app->session->getFlash('success')) {
    echo \kartik\widgets\Alert::widget([
        'type' => \kartik\widgets\Alert::TYPE_SUCCESS,
        'icon' => 'fas fa-ok-circle',
        'body' => Yii::$app->session->getFlash('success'),
        'showSeparator' => true,
    ]);
} elseif (Yii::$app->session->getFlash('error')) {
    echo \kartik\widgets\Alert::widget([
        'type' => \kartik\widgets\Alert::TYPE_DANGER,
        'icon' => 'fas fa-ok-circle',
        'body' => Yii::$app->session->getFlash('error'),
        'showSeparator' => true,
    ]);
}
?>
<header class="header header--fixed">
    <div class="user-button wow fadeInDown" data-wow-duration="1s" data-wow-delay="1s">
        <?php if (Yii::$app->getUser()->isGuest):?>
            <button href="#login-modal" class="user-button__login popup-modal">
                <img src="/assets_b/img/icon/login.svg" alt="">
                <span>Вход</span>
            </button>
            <button href="#registration-modal" class="user-button__signup popup-modal">
                <img src="/assets_b/img/icon/signup.svg" alt="">
                <span>Регистрация</span>
            </button>
        <?php else:?>
            <a href="<?= Yii::$app->urlManager->createUrl(['/cabinet#s-settings'])?>" class="user-button__login">
                <img src="/assets_b/img/icon/signup.svg" alt="">
                <span><?= Yii::$app->user->identity->name?></span>
            </a>
            <a href="<?= Yii::$app->urlManager->createUrl(['/site/logout'])?>" class="user-button__signup">
                <img src="/assets_b/img/icon/login.svg" alt="">
                <span>Выход</span>
            </a>

        <?php endif?>
        <button class="user-button__search">
            <img src="/assets_b/img/icon/search.svg" alt="">
            <span>Поиск</span>
        </button>
    </div>

    <ul class="contact-line wow fadeInDown" data-wow-duration="1s" data-wow-delay="1s">
        <span class="contact-line__item">По вопросам проведения аукционов:</span>
        <li class="contact-line__item">
            <img src="/assets_b/img/icon/mail.svg" alt="">
            <a href="mailto:<?= Yii::$app->params['adminEmail']?>"><?= Yii::$app->params['adminEmail']?></a>
        </li>
        <li class="contact-line__item">
            <img src="/assets_b/img/icon/phone.svg" alt="">
            <?= Yii::$app->params['phone']?>
        </li>
    </ul>

    <?= \app\components\widgets\FavoriteGoodsWidget::widget(['type' => 'index']);?>

    <div class="clearfix"></div>

    <div class="logo wow fadeInLeft" data-wow-duration="1s">
        <a class="logo__img" href="/">
            <img src="/assets_b/img/logo.svg" alt="">
        </a>
        <div class="logo__tagline">
            Аукцион военного <br>антиквариата
        </div>
    </div>

    <?= \app\components\widgets\TopMenuCatsWidget::widget(['type' => 'index']);?>

    <?= \app\components\widgets\TopMenuCatsWidget::widget(['type' => 'index', 'mobile' => true]);?>

</header>

<main>
    <div id="fullpage" class="wow fadeIn" data-wow-duration="1s" data-wow-delay="1s">
        <div class="section section--bg1 " id="section0">
            <?php /** @var  $nextAuction Auction */
            /** @var  $nearestActiveAuction Auction|null */
            /** @var  $pastAuction Auction|null */
            $nearestActiveAuction = null;
            $nearestActiveFlag = null;
            $pastAuction = Auction::find()->where([
                'active' => Auction::PAST_FLAG,
            ])->orderBy(['end_date' => SORT_DESC])->one();
            $nextAuction = $nearestActiveAuction = Auction::find()->where([
                'active' => Auction::ACTIVE_FLAG,
            ])->one();
            $auFlag = $nearestActiveFlag = Auction::ACTIVE_FLAG;
            if (!$nextAuction) {
                $nextAuction = $nearestActiveAuction = Auction::find()->where([
                    'active' => Auction::NEAREST_FLAG,
                ])->one();
                $auFlag = $nearestActiveFlag = Auction::NEAREST_FLAG;
                if (!$nextAuction) {
                    $nextAuction = $pastAuction;
                    $auFlag = Auction::PAST_FLAG;
                    if (!$nextAuction) {
                        $auFlag = Auction::DISABLE_FLAG;
                    }
                }
            }
            ?>
            <?php if($nearestActiveAuction):?>
            <h1 class="section__title section__title--small">
                <a href="<?= $nearestActiveAuction ? Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "next", 'GoodSearch[next_flag]' => $nearestActiveFlag]) : '#'?>">
                    <?= $nearestActiveFlag == Auction::NEAREST_FLAG ? 'Ближайший аукцион' : 'Текущий аукцион'?>
                </a>
            </h1>
            <?php endif?>
            <?php if($pastAuction):?>
            <h1 class="section__title section__title--small">
                <a href="<?= $pastAuction ? Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "next", 'GoodSearch[next_flag]' => Auction::PAST_FLAG]) : '#'?>">
                    <?= 'Прошедший аукцион'?>
                </a>
            </h1>
            <?php endif?>


            <h1 class="section__title section__title--small"><a href="<?= Yii::$app->urlManager->createUrl(['/site/faq'])?>">Узнайте подробнее</a></h1>
            <div class="clearfix"></div>
            <?php if(Yii::$app->params['showIndexVideo']):?>
            <div class="section__video">
                <a class="popup-video" href="https://www.youtube.com/watch?v=DmJoi0XFw7Y">
                    <img src="/assets_b/img/icon/play.svg" alt="">
                </a>
                <span>Смотреть <br>видео презентацию</span>
            </div>
            <?php endif?>

            <?php if(isset($indexGoods[0])):?>
            <div class="section__view-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['/good', 'id' => $indexGoods[0]->id])?>"><img src="/assets_b/img/icon/view.png" alt=""></a>
                <span>Посмотреть <br>данный предмет</span>
            </div>
            <?php endif?>
        </div>

        <div class="section section--bg2" id="section1">
            <?php
            if($auFlag != Auction::DISABLE_FLAG):?>
                <h1 class="section__title">
                    <a href="<?= $nextAuction ? Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "next", 'GoodSearch[next_flag]' => $auFlag]) : '#'?>">
                        <?= $nextAuction ? $nextAuction->name: 'Аукцион скоро будет запущен'?>
                    </a>
                </h1>

                <?php  /*$f = new \yii\i18n\Formatter();
                    $f->timeZone = 'Europe/Moscow';
                    echo "<p>".$f->asDate(time(), 'php:H:i:s')."</p>";
                    echo "<p>".Yii::$app->getFormatter()->asDate($nextAuction->start_date, 'php:H:i:s')."</p>";
                    echo "<p>".\app\components\CommonHelper::getUnixEpoch()."</p>";
                    echo "<p>".\app\components\CommonHelper::getUnixEpoch($nextAuction, 'start_date')."</p>";
*/
                ?>

                <?php if($auFlag == Auction::NEAREST_FLAG):?>
                    <?php
                    if (CommonHelper::getUnixEpoch() >= CommonHelper::getUnixEpoch($nextAuction, 'start_date')):?>
                    <div class="section__countdown">
                        <span class="text">Аукцион скоро будет запущен</span>
                    </div>
                    <?php else:?>
                        <div class="section__countdown">
                        <time class="timer" datetime="<?= Yii::$app->formatter->asDate($nextAuction->start_date, 'php:Y-m-d')."T".Yii::$app->formatter->asDate($nextAuction->start_date, 'php:H:i:s')?>+0300"></time>
                        <span class="text">- ОСТАЛОСЬ ДО НАЧАЛА АУКЦИОНА</span>
                        <div class="clearfix"></div>
                        </div>
                    <?php endif?>
                <?php elseif($auFlag == Auction::ACTIVE_FLAG):?>
                    <div class="section__countdown">
                        <time class="timer" datetime="<?= Yii::$app->formatter->asDate($nextAuction->end_date, 'php:Y-m-d')."T".Yii::$app->formatter->asDate($nextAuction->end_date, 'php:H:i:s')?>+0300"></time>
                        <span class="text">- ОСТАЛОСЬ ДО КОНЦА АУКЦИОНА</span>
                        <div class="clearfix"></div>
                    </div>

                <?php elseif($auFlag == Auction::PAST_FLAG):?>
                <div class="section__countdown">
                    <p><span class="text">АУКЦИОН ЗАВЕРШЕН</span></p>
                    <div class="clearfix"></div>
                    <p><span class="text"> Ближайший аукцион в стадии создания. Следите за новостями.</span></p>
                    <div class="clearfix"></div>

                </div>
                <?php endif?>
            <?php endif?>

            <?php if(Yii::$app->params['showIndexVideo'] && false):?>
            <div class="section__video">
                <a class="popup-video" href="http://www.youtube.com/watch?v=0O2aH4XLbto">
                    <img src="/assets_b/img/icon/play.svg" alt="">
                </a>
                <span>Смотреть инструкцию, <br>как участвовать в аукционе</span>
            </div>
            <?php endif?>

            <?php if(isset($indexGoods[1])):?>
                <div class="section__view-item">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/good', 'id' => $indexGoods[1]->id])?>"><img src="/assets_b/img/icon/view.png" alt=""></a>
                    <span>Посмотреть <br>данный предмет</span>
                </div>
            <?php endif?>
        </div>

        <div class="section section--bg3" id="section2">
            <h1 class="section__title">Ознакомьтесь с каталогом всех лотов!</h1>

            <div class="catalog">
                <div class="row">
                    <?= \app\components\widgets\FooterCatsWidget::widget(['type' => 'index']);?>
                </div>
            </div>

            <div class="catalog catalog--mobile">
                <ul class="catalog__list">
                    <li class="catalog__item catalog__item--mobile">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/good/index'])?>">Холодное оружие Германии</a>
                    </li>
                    <li class="catalog__item catalog__item--mobile">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/good/index'])?>">Холодное оружие других стран</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="section section--bg4" id="section3">
            <h1 class="section__title section__title--bold">Остались вопросы?</h1>

            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <?php $callBackForm = new \app\models\CallBackForm();
                    $callBackForm->renderIndex();?>
                    <div class="section__form section__form--2 clearfix">
                        <h6 class="section__form-title section__form-title--2">
                            Напишите нам и мы свяжимся с Вами <br>в ближайшее время.
                        </h6>
                        <button href="#question-modal" class="section__form-button section__form-button--2 popup-modal">
                            задать вопрос
                        </button>
                        <a class="section__form-link popup-modal" href="#comment-modal">Оставить отзыв</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<ul class="section-navigation wow fadeInRight" id="section-navigation" data-wow-duration="1s" data-wow-delay="1s">
    <li class="section-navigation__item" data-menuanchor="section-about" class="active">
        <a class="section-navigation__link" href="#section-about"><span>О нас</span></a>
    </li>
    <li class="section-navigation__item" data-menuanchor="section-auction">
        <a class="section-navigation__link" href="#section-auction"><span>
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
            </span></a>
    </li>
    <li class="section-navigation__item" data-menuanchor="section-lots">
        <a class="section-navigation__link" href="#section-lots"><span>Все лоты</span></a>
    </li>
    <li class="section-navigation__item" data-menuanchor="section-contacts">
        <a class="section-navigation__link" href="#section-contacts"><span>Контакты</span></a>
    </li>
</ul>

<div class="fixed-search">
    <button class="fixed-search__close">
        <img src="assets_b/img/icon/close.svg" alt="">
    </button>

    <?= \app\components\widgets\SearchFormWidget::widget(['type' => 'index']);?>

    <div class="catalog catalog--search">
        <div class="row">
            <?= \app\components\widgets\FooterCatsWidget::widget(['type' => 'index']);?>
        </div>
    </div>
</div>

<div id="registration-modal" class="mfp-hide basic-modal basic-modal--registration">
    <?php $signUpModel = new \app\models\auth\SignupForm();
    $form = ActiveForm::begin([
        'id' => 'form-signup',
        'action' => Yii::$app->urlManager->createUrl(['/site/signup']),
        'class' => 'form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form__label'],
            'inputOptions' => ['class' => 'form__input'],
        ],
    ]); ?>
    <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
    <div class="col-xs-12">
        <h5 class="basic-modal__title">Регистрация</h5>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Заполните пожалуйста поля!</h6>


        <?= $form->field($signUpModel, 'fio', [ 'options' => ['class' => 'form__group']]) ?>
        <?= $form->field($signUpModel, 'phone', [ 'options' => ['class' => 'form__group']]) ?>
        <?= $form->field($signUpModel, 'email', [ 'options' => ['class' => 'form__group']]) ?>



    </div>
    <!--<div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Войти через социальные сети:</h6>
        <p class="basic-modal__text">Вы можете войти, используя свою учётную запись в социальных сетях.</p>

        <ul class="basic-modal__social">
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/vk.svg" alt="">Вконтакте</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/facebook.svg" alt="">Facebook</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/twitter.svg" alt="">Twitter</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/odnoc.svg" alt="">Однокласники</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/googleplus.svg" alt="">Google</a>
            </li>
        </ul>
    </div>-->
    <div class="col-xs-12">
        <?= Html::submitButton('начать покупки', ['class' => 'default-button', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="clearfix"></div>
</div>

<div id="login-modal" class="mfp-hide basic-modal basic-modal--login">
    <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
    <div class="col-xs-12">
        <h5 class="basic-modal__title">вход</h5>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Введите пароль и логин:</h6>

        <?php $form = ActiveForm::begin([
            'action' => Yii::$app->urlManager->createUrl(['/site/login']),
            'id' => 'login-form',
            'class' => 'form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'labelOptions' => ['class' => 'form__label'],
                'inputOptions' => ['class' => 'form__input'],
            ],
        ]); ?>
        <?= $form->field($loginModel, 'username', [
            'options' => ['class' => 'form__group']
        ]) ?>
        <?= $form->field($loginModel, 'password', [
            'options' => ['class' => 'form__group']
        ])->passwordInput() ?>
        <?= Html::submitButton('вход', ['class' => 'default-button default-button--login', 'name' => 'login-button']) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <!--<div class="col-xs-12 col-sm-6">
        <h6 class="basic-modal__title basic-modal__title--second">Войти через социальные сети:</h6>
        <p class="basic-modal__text">Вы можете войти, используя свою учётную запись в социальных сетях.</p>

        <ul class="basic-modal__social">
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/vk.svg" alt="">Вконтакте</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/facebook.svg" alt="">Facebook</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/twitter.svg" alt="">Twitter</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/odnoc.svg" alt="">Однокласники</a>
            </li>
            <li class="basic-modal__social-item">
                <a href="#"><img src="/assets_b/img/social/googleplus.svg" alt="">Google</a>
            </li>
        </ul>
    </div>-->
    <div class="clearfix"></div>
</div>

<div id="question-modal" class="mfp-hide basic-modal basic-modal--login">
    <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
    <div class="col-xs-12 col-sm-12">
        <h6 class="basic-modal__title basic-modal__title--second">Напишите нам и мы свяжимся с Вами в ближайшее время.</h6>
        <?php $questionModel = new ContactForm();
        $questionModel->type = ContactForm::TYPE_QUESTION;
        echo $this->render('_contacts_form', ['model' => $questionModel]); ?>
    </div>
    <div class="clearfix"></div>
</div>

<div id="comment-modal" class="mfp-hide basic-modal basic-modal--login">
    <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
    <div class="col-xs-12 col-sm-12">
        <h6 class="basic-modal__title basic-modal__title--second">Напишите Ваше предложение или замечание и мы свяжемся с Вами в ближайшее время.</h6>
        <?php $commentModel = new ContactForm();
        $commentModel->type = ContactForm::TYPE_COMMENT;
        echo $this->render('_contacts_form', ['model' => $commentModel]); ?>
    </div>
    <div class="clearfix"></div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
