<?php

use yii\helpers\Html;
use \yii\bootstrap\ActiveForm;
use \app\models\auction\Auction;

/* @var $this yii\web\View */
/* @var $userSettingsForm \app\models\auth\UserSettingsForm */
/* @var $deliveryForm \app\models\auth\DeliveryForm */

$this->title = 'Личный кабинет';
$bc[] = $this->title;

$cart = new \app\components\shop\MyShoppingCart();
$positionsCont = $cart->getPositions();
?>
<?php if (!Yii::$app->user->isGuest):?>
<div class="cabinet-navigation-wrap">
    <ul class="cabinet-navigation">
        <li class="cabinet-navigation__item cabinet-navigation__item--user">
            <img src="/assets_b/img/icon/user.svg" alt="">
            <?= Yii::$app->user->isGuest ? 'Гость' : Yii::$app->user->identity->name?>
        </li>
        <?php if(!Yii::$app->user->isGuest):?>
        <li class="cabinet-navigation__item">
            <a href="#s-basket">
                <img class="default-img" src="/assets_b/img/icon/basket4.svg" alt="">
                <img class="active-img" src="/assets_b/img/icon/basket4--white.svg" alt="">
                <span>Ваша корзина</span>
            </a>
        </li>
        <li class="cabinet-navigation__item">
            <a href="#s-liked">
                <img class="default-img" src="/assets_b/img/icon/star2.svg" alt="">
                <img class="active-img" src="/assets_b/img/icon/star.svg" alt="">
                <span>Избранные лоты</span>
            </a>
        </li>
        <li class="cabinet-navigation__item">
            <a href="#s-settings">
                <img class="default-img" src="/assets_b/img/icon/settings.svg" alt="">
                <img class="active-img" src="/assets_b/img/icon/settings--white.svg" alt="">
                <span>Настройки</span>
            </a>
        </li>
        <?php endif?>
        <li class="cabinet-navigation__item">
            <a href="#s-viewed">
                <img class="default-img" src="/assets_b/img/icon/eye2.svg" alt="">
                <img class="active-img" src="/assets_b/img/icon/eye.svg" alt="">
                <span>Просмотреные лоты</span>
            </a>
        </li>

        <div class="clearfix"></div>
    </ul>
</div>
<?php endif?>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-9">
        <div class="cabinet">
            <?= \yii\widgets\Breadcrumbs::widget(['links' => $bc]);?>

            <h2 class="content-title"><?= $this->title?></h2>

            <?php if(!Yii::$app->user->isGuest):?>
            <div class="cabinet__section cabinet__section--basket" id="s-basket">
                <h3 class="cabinet__title">Корзина</h3>

                <div class="table-responsive">
                    <?php
                         $itemsProvider = new \yii\data\ArrayDataProvider([
                            'allModels' => $positionsCont->positions,
                            'pagination' => [
                                'pageSize' => false,
                            ],
                        ]);
                        echo \yii\grid\GridView::widget([
                            'dataProvider' => $itemsProvider,
                            'summary'=>'',
                            'tableOptions' => ['class' => 'table cabinet__table'],
                            'dataColumnClass' => \app\components\grid\CartColumn::className(),
                            'showFooter' => true,
                            'columns' => [
                                [
                                    'format' => 'raw',
                                    'label' => 'Фото',
                                    'value' => function ($data) {
                                        /** @var \app\models\auction\Good $data */
                                        return '<img src="'.$data->img_path.'" alt="'.$data->name.'">';
                                    },
                                ],
                                [
                                    'attribute' => 'name',
                                    'label' => 'Наименование',
                                    'format' => 'raw',
                                    'value' => function ($data) {
                                        /** @var \app\models\auction\Good $data */
                                        return "<a href='".Yii::$app->urlManager->createUrl(['/good', 'id' => $data->id])."'>$data->name</a>";
                                    },
                                ],
                                [
                                    'label' => 'Ваша ставка',
                                    'format' => 'raw',
                                    'value' => function ($data) {
                                        /** @var \app\models\auction\Good $data */
                                        $html = "<span class='price'>".Yii::$app->formatter->asDecimal($data->user_bid)."</span>";


                                        return $html;
                                    },
                                ],
                                [
                                    'label' => 'Наибольшая ставка',
                                    'format' => 'raw',
                                    'value' => function ($data) {
                                        /** @var \app\models\auction\Good $data */
                                        $html = "<div class='row'>";
                                        $html = "<div class='col-xs-12'><span class='price'>".Yii::$app->formatter->asDecimal($data->max_bid->value)."</span></div>";
                                        if ($data->auction->active == Auction::PAST_FLAG) {
                                            $html .= '<div class="col-xs-12">';
                                            if($data->win_bid_id && $data->win_bid->user_id == Yii::$app->user->id){
                                                $html .= ($data->win_bid->user_id == Yii::$app->user->id) ? '<br/><span class="text-success">Вы победили</span>' : '<br/><span>Проигрыш</span>';
                                            }
                                            $html .= '</div>';
                                        }
                                        $html .= '</div>';

                                        return $html;
                                    },
                                ],
                            ]
                        ]);
                     ?>
                </div>

                <?php if (!empty($positionsCont->positions)):?>
                <ul class="cabinet__table-mobile">
                    <?php /** @var \app\models\auction\Good $goodItem */
                    foreach($positionsCont->positions as $goodItem):?>
                    <li class="cabinet__table-mobile-item">
                        <img src="<?= $goodItem->img_path?>" alt="">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/good', 'id' => $goodItem->id])?>"><?= $goodItem->name?></a>
                        <div class="box-wrap">
                            <span>Наибольшая ставка</span>
                            <span class="price"><?= Yii::$app->formatter->asDecimal($goodItem->max_bid->value)?></span>
                        </div>
                        <div class="box-wrap">
                            <span>Ваша ставка</span>
                            <span class="price"><?= $goodItem->user_bid?></span>
                            <?php if($goodItem->win_bid_id):?>
                                <?php if($goodItem->win_bid->user_id == Yii::$app->user->id):?>
                                    <span class="text-success">Вы победили</span>
                                <?php endif?>
                            <?php endif?>
                        </div>
                    </li>
                    <?php endforeach?>
                </ul>
                <?php endif?>

                <div class="cabinet__result">
                    ИТОГО: <span class="price"><?= $cart->getCost()?></span>
                </div>
            </div>

            <div class="cabinet__section cabinet__section--delivery">
                <h3 class="cabinet__title">оформить доставку ваших лотов</h3>
                <span>Доставка осуществляется только после окончания аукциона при условии Вашей победы в нем</span>

                <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'options' => ['class' => 'form cabinet__form'],
                    'action' => Yii::$app->urlManager->createUrl('/cabinet#s-settings'),
                    'fieldConfig' => [
                        'labelOptions' => ['class' => 'form__label'],
                        'inputOptions' => ['class' => 'form__input'],
                    ]
                ]);?>
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <?= $form->field($deliveryForm, 'fio', ['options' => ['class' => 'form__group']]);?>
                        <?=  $form->field($deliveryForm, 'phone', ['options' => ['class' => 'form__group']]);?>
                        <?=  $form->field($deliveryForm, 'email', ['options' => ['class' => 'form__group']]);?>
                        <div class="col-xs-12">

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <?=  $form->field($deliveryForm, 'address', ['options' => ['class' => 'form__group']]);?>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <?=  $form->field($deliveryForm, 'comment', ['options' => ['class' => 'form__group']])->textarea(['class' => 'form__textarea']);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <?=  Html::submitButton('Оформить', ['class' => 'default-button default-button--contents']);?>
                    </div>

                </div>
                <?php ActiveForm::end();?>

                <!--<form action="#" class="form cabinet__form">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <div class="form__group">
                                <label class="form__label" for="">ФИО:</label>
                                <input class="form__input" type="text">
                            </div>
                            <div class="form__group">
                                <label class="form__label" for="">Телефон:</label>
                                <input class="form__input" type="text">
                            </div>
                            <div class="form__group">
                                <label class="form__label" for="">Email:</label>
                                <input class="form__input" type="text">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-8">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form__group">
                                        <label class="form__label" for="">Город:</label>
                                        <select class="form__select">
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form__group">
                                        <label class="form__label" for="">Адрес:</label>
                                        <input class="form__input" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="form__group">
                                <label class="form__label" for="">Комментарий:</label>
                                <textarea class="form__textarea"></textarea>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <button class="default-button default-button--contents">Оформить</button>
                        </div>
                    </div>
                </form>-->
            </div>

            <div class="cabinet__section cabinet__section--liked cabinet__section--before" id="s-liked">
                <h3 class="cabinet__title">избранные лоты</h3>

                <div class="row">
                    <div class="auction-list clearfix">
                        <?php
                        $viewedProvider = new \yii\data\ArrayDataProvider([
                            'allModels' => $positionsCont->favorite,
                            'pagination' => [
                                'pageSize' => false,
                            ],
                        ]);
                        echo \yii\widgets\ListView::widget([
                            'dataProvider' => $viewedProvider,
                            'itemView' => '/good/_view',
                            'viewParams' => ['cart' => $cart],
                            'itemOptions' => ['class' => 'lot_item col-xs-12 col-md-6 col-lg-4'],
                            'summary'=>'',
                            'emptyText' => '<div class="col-xs-12"><p>Ничего не найдено</p></div>'

                        ]);?>
                    </div>
                </div>
            </div>

            <div class="cabinet__section cabinet__section--settings" id="s-settings">
                <h3 class="cabinet__title">Настройки</h3>

                <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'options' => ['class' => 'form cabinet__form'],
                    'action' => Yii::$app->urlManager->createUrl('/cabinet#s-settings'),
                    'fieldConfig' => [
                        'labelOptions' => ['class' => 'form__label'],
                        'inputOptions' => ['class' => 'form__input'],
                    ]
                ]);?>
                <div class="row">
                    <?= $form->field($userSettingsForm, 'fio', ['options' => ['class' => 'col-xs-12 col-sm-4']]);?>
                    <?=  $form->field($userSettingsForm, 'phone', ['options' => ['class' => 'col-xs-12 col-sm-4']]);?>
                    <?php //echo  $form->field($userSettingsForm, 'email', ['options' => ['class' => 'col-xs-12 col-sm-4']]);?>
                    <?=  $form->field($userSettingsForm, 'password', ['options' => ['class' => 'col-xs-12 col-sm-4']])->passwordInput();?>
                    <div class="col-xs-12">
                    <?=  Html::submitButton('Изменить', ['class' => 'default-button default-button--contents']);?>
                    </div>
                </div>
                <?php ActiveForm::end();?>

            </div>
            <?php endif?>

            <div class="cabinet__section cabinet__section--viewed cabinet__section--before" id="s-viewed">
                <h3 class="cabinet__title">просмотренные лоты</h3>
                <div class="row">
                    <div class="auction-list clearfix">
                        <?php
                        $viewedProvider = new \yii\data\ArrayDataProvider([
                            'allModels' => $positionsCont->viewed,
                            'pagination' => [
                                'pageSize' => false,
                            ],
                        ]);
                        echo \yii\widgets\ListView::widget([
                            'dataProvider' => $viewedProvider,
                            'itemView' => '/good/_view',
                            'viewParams' => ['cart' => $cart],
                            'itemOptions' => ['class' => 'lot_item col-xs-12 col-md-6 col-lg-4'],
                            'summary'=>'',
                            'emptyText' => '<div class="col-xs-12"><p>Ничего не найдено</p></div>'
                        ]);?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>