<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use \yii\widgets\ActiveForm;
use app\components\CommonHelper;
use app\models\auction\GoodStringSearch;
use \app\models\ContactForm;
use \app\models\auction\GoodFavorite;

\app\assets_b\CommonAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <title><?= Html::encode($this->title) ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta charset="<?= Yii::$app->charset ?>">
  <!--[if IE]>
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"><![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>

  <!-- Font -->
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet'
        type='text/css'>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="header header--relative">
  <div class="header__topline">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
            <?php if (Yii::$app->getUser()->isGuest): ?>
              <div class="user-button user-button--second">
                <button href="#login-modal" class="user-button__login popup-modal">
                  <img src="/assets_b/img/icon/login.svg" alt="">
                  <span>Вход</span>
                </button>
                <button href="#registration-modal" class="user-button__signup popup-modal">
                  <img src="/assets_b/img/icon/signup.svg" alt="">
                  <span>Регистрация</span>
                </button>
              </div>
            <?php else: ?>
              <div class="user-button user-button--second">
                <a href="<?= Yii::$app->urlManager->createUrl(['/cabinet#s-settings']) ?>" class="user-button__login">
                  <img src="/assets_b/img/icon/signup.svg" alt="">
                  <span><?= Yii::$app->user->identity->name ?></span>
                </a>
                <a href="<?= Yii::$app->urlManager->createUrl(['/site/logout']) ?>" class="user-button__signup">
                  <img src="/assets_b/img/icon/login.svg" alt="">
                  <span>Выход</span>
                </a>
              </div>
            <?php endif ?>

          <ul class="contact-line contact-line--second">
            <span class="contact-line__item">По вопросам проведения аукционов:</span>
            <li class="contact-line__item">
              <img src="/assets_b/img/icon/mail.svg" alt="">
              <a href="mailto:<?= Yii::$app->params['adminEmail'] ?>"><?= Yii::$app->params['adminEmail'] ?></a>
            </li>
            <li class="contact-line__item">
              <img src="/assets_b/img/icon/phone.svg" alt="">
                <?= Yii::$app->params['phone'] ?>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="header__midline">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12">
          <div class="logo logo--green">
            <a class="logo__img" href="/">
              <img src="/assets_b/img/logo--green.svg" alt="">
            </a>
            <div class="logo__tagline">
              Аукцион военного <br>антиквариата
            </div>
          </div>

            <?= \app\components\widgets\SearchFormWidget::widget([
                'type' => 'main',
                'model' => isset($this->params[CommonHelper::getShortClassName(GoodStringSearch::className())]) ? $this->params[CommonHelper::getShortClassName(GoodStringSearch::className())] : null,
            ]); ?>

            <?php if (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()) {
                echo "<div style='margin: 38px 7px 0px 30px; display: inline-block; float: left;'>";
                echo Html::a(Yii::t('app', 'Dashboard'), Yii::$app->urlManager->createUrl('/admin/good/index'), ['class' => 'btn btn-default']);
                echo "</div>";
            } ?>
            <?= \app\components\widgets\FavoriteGoodsWidget::widget(['type' => 'main']); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <ul class="navigation-desktop navigation-desktop--green">
              <?= \app\components\widgets\TopMenuCatsWidget::widget(['type' => 'main']); ?>
          </ul>
            <?= \app\components\widgets\TopMenuCatsWidget::widget(['type' => 'main', 'mobile' => true]); ?>
        </div>
      </div>
    </div>
  </div>
</header>
<main>

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
  <div class="container-fluid">
      <?= $content ?>
  </div>

    <?php $subscribeModel = isset($this->params['subscribeForm']) ? $this->params['subscribeForm'] : new \app\models\SubscribeForm();
    echo $this->render('_subscribe_form', ['model' => $subscribeModel]); ?>

  <div class="footer-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12 col-md-3">
          <div class="footer-content__logo">
            <a href="#">
              <img src="/assets_b/img/logo--green.svg" alt="">
            </a>
          </div>

          <div class="footer-content__phones">
            <p>8 (800) <span>222-19-41</span></p>
          </div>

          <div class="footer-content__mail">
            <a href="mailto:<?= Yii::$app->params['adminEmail'] ?>"><?= Yii::$app->params['adminEmail'] ?></a>
            <div class="clearfix"></div>
            <a href="mailto:<?= Yii::$app->params['supportEmail'] ?>"><?= Yii::$app->params['supportEmail'] ?></a>
          </div>
        </div>

        
        <div class="col-xs-12 col-sm-4 col-md-3">
          <h6 class="footer-content__title">Аукцион</h6>
          <ul class="catalog__list catalog__list--50 catalog__list--footer">
            <h6 class="catalog__title">
              о нас:
            </h6>
            <li class="catalog__item">
              <a href="<?= Yii::$app->urlManager->createUrl(['/site/faq']) ?>">О аукционе</a>
            </li>
            <li class="catalog__item">
              <a href="<?= Yii::$app->urlManager->createUrl(['/site/faq']) ?>">Как участвовать?</a>
            </li>
            <li class="catalog__item">
              <a href="<?= Yii::$app->urlManager->createUrl(['/site/contacts',
                  CommonHelper::getShortClassName(ContactForm::className()) => ['type' => ContactForm::TYPE_QUESTION]]) ?>">Задать
                вопрос</a>
            </li>
            <li class="catalog__item">
              <a href="<?= Yii::$app->urlManager->createUrl(['/site/contacts']) ?>">Контакты</a>
            </li>
          </ul>

        </div>
      </div>
    </div>
  </div>

</main>

<footer class="footer">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-md-4">
        <ul class="footer__social">
          <li class="footer__social-item">
            <a href="https://vk.com/warstory"><img src="/assets_b/img/icon/footer/vk2 1.svg" alt=""></a>
          </li>
		   <li class="footer__social-item">
            <a href="https://www.instagram.com/warstory_ru/"><img src="/assets_b/img/icon/footer/in2.1.svg" alt=""></a>
          </li>
		   <li class="footer__social-item">
            <a href="https://t.me/warstory_ru"><img src="/assets_b/img/icon/footer/te2.1.svg" alt=""></a>
          </li>
          <!--<li class="footer__social-item">
              <a href="#"><img src="/assets_b/img/social/facebook2.svg" alt=""></a>
          </li>
          <li class="footer__social-item">
              <a href="#"><img src="/assets_b/img/social/twitter2.svg" alt=""></a>
          </li>-->
        </ul>
      </div>
      <div class="col-xs-12 col-md-8">
        <div class="footer__copyright">
          © <?= date('Y') ?> <a href="/"><?= Yii::$app->params['domain'] ?></a> - аукцион военного антиквариата
        </div>
      </div>
    </div>
  </div>
</footer>

<div class="fixed-search">
  <button class="fixed-search__close">
    <img src="/assets_b/img/icon/close.svg" alt="">
  </button>

  <form action="#" class="fixed-search__form">
    <input class="fixed-search__input" type="text" placeholder="Поиск по сайту">
    <button class="fixed-search__button"><img src="/assets_b/img/icon/search.png" alt=""></button>
  </form>

  <div class="catalog catalog--search">

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


      <?= $form->field($signUpModel, 'fio', ['options' => ['class' => 'form__group']]) ?>
      <?= $form->field($signUpModel, 'phone', ['options' => ['class' => 'form__group']]) ?>
      <?= $form->field($signUpModel, 'email', ['options' => ['class' => 'form__group']]) ?>
    <div class="form__group">
      <input type="checkbox" id="test1" checked="checked">
      <label for="test1">Я ознакомлен <br>
        <a href="#">с условиями предоставляемых услуг</a>
      </label>
    </div>


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

      <?php
      $loginModel = new \app\models\auth\LoginForm();
      $form = ActiveForm::begin([
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
          'template' => "{label}\n{input}\n{hint}\n{error}",
          'options' => ['class' => 'form__group']
      ]) ?>
      <?= $form->field($loginModel, 'password', [
          'template' => "{label}\n{input}\n{hint}\n{error}",
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

<div id="preview-modal" class="mfp-hide basic-modal basic-modal--preview">
  <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
  <div class="row">
    <div class="col-xs-12 col-md-7">

      <div id="sync1" class="owl-carousel lot-slider popup-gallery">

      </div>

      <div class="thumbnail-wrap">
        <div id="sync2" class="owl-carousel thumbnail-slider">
          <div class="item">
            <img src="/assets_b/img/slider/1.jpg" alt="">
          </div>
          <div class="item item--video">
            <img src="/assets_b/img/slider/2.jpg" alt="">
          </div>
          <div class="item">
            <img src="/assets_b/img/slider/3.jpg" alt="">
          </div>
          <div class="item">
            <img src="/assets_b/img/slider/1.jpg" alt="">
          </div>
          <div class="item item--video">
            <img src="/assets_b/img/slider/2.jpg" alt="">
          </div>
          <div class="item">
            <img src="/assets_b/img/slider/3.jpg" alt="">
          </div>
        </div>

        <div class="lot-navigation">
          <span class="prev"><img src="/assets_b/img/icon/arrow-left.png" alt=""></span>
          <span class="next"><img src="/assets_b/img/icon/arrow-right.png" alt=""></span>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-5">
      <div class="lot-content lot-content--popup">
        <h2 class="lot-content__title"><a href="#">Полицейский штык с темляком и подвесом, Lüneschloss</a></h2>
        <time class="timer" datetime="2016-06-21T13:43:00"></time>

        <form action="#" class="lot-content__form clearfix">
          <div class="lot-content__price">
            <p>Текущая цена:</p>
            <span>58 000</span>
          </div>
          <div class="form-group">
            <input class="lot-content__form-input" type="text" placeholder="Введите сумму ставки">
            <button class="lot-content__form-button">СДЕЛАТЬ СТАВКУ</button>
          </div>
        </form>

        <div class="feedback feedback--popup">
          <h6 class="feedback__title">Если вы хотите купить <span>подобный лот?</span></h6>
          <div class="feedback__phone">
            Позвоните нам <span><?= Yii::$app->params['phone'] ?></span>
          </div>
          <div class="feedback__call">
            или закажите
            <button href="#call-modal" class="popup-modal">обратный звонок</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>

<div id="call-modal" class="mfp-hide basic-modal basic-modal--call">
  <a class="basic-modal__dismiss" href="#"><img src="/assets_b/img/icon/close-modal.png" alt=""></a>
  <div class="col-xs-12">
    <h5 class="basic-modal__title">Заказать обратный звонок</h5>
    <p class="basic-modal__text">Закажите бесплатный звонок и наш специалист свяжется с Вами в ближайшее время!</p>
  </div>
  <div class="col-xs-12">
      <?php $callBackForm = new \app\models\CallBackForm();
      $callBackForm->renderMain(); ?>
  </div>
  <div class="clearfix"></div>
</div>

<?php
echo Html::tag('span', GoodFavorite::ACTION_ADD, ['id' => 'favorite-action-add', 'class' => 'hide']);
echo Html::tag('span', GoodFavorite::ACTION_REMOVE, ['id' => 'favorite-action-remove', 'class' => 'hide']);
?>



<?php $this->endBody() ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
  (w[c] = w[c] || []).push(function () {
    try {
      w.yaCounter39791795 = new Ya.Metrika({
        id: 39791795,
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true
      })
    } catch (e) { }
  })

  var n = d.getElementsByTagName('script')[0],
    s = d.createElement('script'),
    f = function () { n.parentNode.insertBefore(s, n) }
  s.type = 'text/javascript'
  s.async = true
  s.src = 'https://mc.yandex.ru/metrika/watch.js'

  if (w.opera == '[object Opera]') {
    d.addEventListener('DOMContentLoaded', f, false)
  } else { f() }
})(document, window, 'yandex_metrika_callbacks')
</script>
<noscript>
  <div><img src="https://mc.yandex.ru/watch/39791795" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
<?php $this->endPage() ?>
