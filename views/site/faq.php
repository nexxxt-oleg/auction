<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $exception Exception */

$this->title = 'FAQ';
$bc[] = $this->title;
\app\assets_b\SiteFaqAsset::register($this);
?>
<div class="row">
    <div class="col-xs-12">
        <?= \yii\widgets\Breadcrumbs::widget(['links' => $bc]);?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-push-8 col-md-3 col-md-push-9">
        <ul class="faq-tabs" role="tablist">
            <li role="presentation" class="faq-tabs__item active">
                <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">О проекте</a>
            </li>
            <li role="presentation" class="faq-tabs__item">
                <a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">Как участвовать</a>
            </li>
            <!--<li role="presentation" class="faq-tabs__item">
                <a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">Как участвовать</a>
            </li>
            <li role="presentation" class="faq-tabs__item">
                <a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">О проекте</a>
            </li>-->
        </ul>
    </div>

    <div class="col-xs-12 col-sm-8 col-sm-pull-4 col-md-9 col-md-pull-3">
        <div class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="tab1">
                <h2 class="content-title">О проекте</h2>
				<p class="content-text">Warstory Auction (Аукцион военного антиквариата) - это российская торговая площадка, где представлены антикварные предметы периода первой и второй мировых войн. 
				<br/>Лоты аукциона структурированы в каталог с категориями и фильтрами, с помощью которых можно найти интересующие вас предметы. Подлинность представленных лотов гарантирована экспертами Warstory. 
				<br/>Каждый аукцион – это подборка предметов военного антиквариата, с ограниченной по времени возможностью проведения торгов. К торгам допускаются зарегистрированные пользователи. 
				<br/>Дата аукциона, сроки и лоты анонсируются заранее на сайте warstory.ru и в социальных сетях сообщества Warstory.

				</p>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab2">
                <h2 class="content-title">Как участвовать в аукционе?</h2>

                <p class="content-text">
				Аукцион – форма торгов, где покупателем товара становится зарегистрированный пользователь, сделавший максимальную ставку.
				<br/>Warstory Auction использует систему ручных ставок, которая осуществляется с помощью кнопки «Сделать ставку» на странице лота. 
				<br/>Участвовать в аукционе просто, необходимо следовать следующему алгоритму:

				<ol style="margin-bottom:50px;font-size:16px;"><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					Зарегистрируйтесь на сайте и подтвердите свой аккаунт через электронную почту.
				</li><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					С помощью поиска и каталога просмотрите список представленных на ближайшем аукционе лотов.
				</li><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					Сделайте ставку по понравившимся Вам лотам. Система принимает ставку выше текущей цены. Торги стартуют с начальной цены которая объявлена заранее.
				</li><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					Вы можете добавлять лоты в избранное и следить за ними через личный кабинет. Лоты, по которым вы сделаете ставку, отображаются в «Личном кабинете» во вкладке «Ваша корзина». В этой вкладке видна ваша и максимальная ставка.
				</li><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					Увеличить ставку можно неограниченное количество раз в течение аукциона.
				</li><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					Победителем признается пользователь, сделавший максимальную на момент окончания аукциона ставку. Остаток времени до окончания аукциона выводится на станице лота.
				</li><li style="position: relative; padding: 10px 15px; margin-bottom: -1px;">
					В течение 24 часов после окончания торгов администрация аукциона связывается с победителем и уточняет сроки и порядок оплаты/доставки.
				</li></ol>
			</p>

                
            </div>

            
<!--
            <div role="tabpanel" class="tab-pane fade" id="tab3">
                <h2 class="content-title">Как участвовать</h2>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab4">
                <h2 class="content-title">О проекте</h2>
            </div>
			-->
        </div>
    </div>


</div>