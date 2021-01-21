<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'viewParams' => ['cart' => new \app\components\shop\MyShoppingCart()],
    'itemOptions' => ['class' => 'lot_item col-xs-12 col-md-6 col-lg-4'],
    'summary'=>'',
    'pager' => [
        'class' => \kop\y2sp\ScrollPager::className(),
        'item' => '.lot_item',
        'triggerTemplate' => '
            <div class="col-xs-12">
                <button class="auction-list__more"><img src="/assets_b/img/icon/more.svg" alt="">{text}</button>
            </div>',
        'triggerText' => 'Показать еще',
        'noneLeftText' => '',
        'eventOnRendered' => new \yii\web\JsExpression("function(arItems){
            $('.popup-modal-item').magnificPopup({
                preloader: false,
                modal: true,
                callbacks: window.popupCallbacks
            });
            $('.auction-item__countdown time').countDown();
        }"),
    ],
]);