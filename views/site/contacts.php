<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */
/* @var $model \app\models\ContactForm */

$this->title = 'Контакты';
$bc[] = ['label' => 'FAQ', 'url' => Yii::$app->urlManager->createUrl(['/site/faq'])];
$bc[] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <?= \yii\widgets\Breadcrumbs::widget(['links' => $bc]);?>
    </div>

    <div class="col-xs-12">
        <h2 class="content-title"><?= $this->title?></h2>
    </div>
</div>

<div class="row">
    <div class="contacts-page">
        <div class="col-xs-12 col-sm-4">
            <p class="contacts-page__text">Все вопросы и предложения можно оставить по следующим координатам:</p>

            <div class="contacts-page__box contacts-page__box--phone">
                <img src="/assets_b/img/icon/contacts/phone2 1.svg" alt="">
                <h6 class="contacts-page__box-title">Телефоны:</h6>
                <p class="contacts-page__box-text"><?= Yii::$app->params['phone']?></p>
            </div>
			<div class="contacts-page__box contacts-page__box--phone">
                <img src="/assets_b/img/icon/contacts/telegram 1.svg" alt="">
                <h6 class="contacts-page__box-title">Telegram:</h6>
                <p class="contacts-page__box-text"><a href="https://t.me/warstory_ru">warstory_ru</a></p>
            </div>
			<div class="contacts-page__box contacts-page__box--phone">
                <img src="/assets_b/img/icon/contacts/instagram 1.svg" alt="">
                <h6 class="contacts-page__box-title">Instagram:</h6>
                <p class="contacts-page__box-text"><a href="https://www.instagram.com/warstory_ru/">warstory_ru</a></p>
            </div>

            <div class="contacts-page__box contacts-page__box--mail">
                <img src="/assets_b/img/icon/contacts/mail2.svg" alt="">
                <h6 class="contacts-page__box-title">Администрация сайта:</h6>
                <p class="contacts-page__box-text"><a href="mailto:<?= Yii::$app->params['adminEmail']?>"><?= Yii::$app->params['adminEmail']?></a></p>
            </div>

            <div class="contacts-page__box contacts-page__box--basket">
                <img src="/assets_b/img/icon/contacts/basket3 1.svg" alt="">
                <h6 class="contacts-page__box-title">Вопросы по приобретению:</h6>
                <p class="contacts-page__box-text"><a href="mailto:<?= Yii::$app->params['supportEmail']?>"><?= Yii::$app->params['supportEmail']?></a></p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8">
            <?= $this->render('_contacts_form', ['model' => $model,])?>
        </div>

        <div class="clearfix"></div>
    </div>
</div>