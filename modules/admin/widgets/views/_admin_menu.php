<?php
use kartik\nav\NavX;
use \yii\bootstrap\NavBar;

NavBar::begin();
echo NavX::widget([
    'activateParents' => true,
    'options' => ['class' => 'navbar-nav'],
    'items' => [
        ['label' => 'Лоты', 'url' => [Yii::$app->urlManager->createUrl('admin/good/index')]],
        ['label' => 'Цены', 'url' => [Yii::$app->urlManager->createUrl('admin/price/index')]],
        ['label' => 'Аукционы', 'url' => [Yii::$app->urlManager->createUrl('admin/auction/index')]],
        ['label' => 'Категории', 'url' => [Yii::$app->urlManager->createUrl('admin/category/index')]],
        ['label' => 'Фильтры', 'url' => [Yii::$app->urlManager->createUrl('admin/filter/index')]],
        ['label' => 'Почта', 'url' => [Yii::$app->urlManager->createUrl('admin/mail/index')]],
        ['label' => 'Пользователи', 'url' => [Yii::$app->urlManager->createUrl('admin/user/index')]],
        ['label' => 'Интервалы ставок', 'url' => [Yii::$app->urlManager->createUrl('admin/interval/index')]],
        ['label' => 'Статистика лотов', 'items' => [
            ['label' => 'Статистика просмотров', 'url' => [Yii::$app->urlManager->createUrl('admin/goodstat/viewed')]],
            ['label' => 'Статистика избранного', 'url' => [Yii::$app->urlManager->createUrl('admin/goodstat/favorite')]],
            ['label' => 'Ставки', 'url' => [Yii::$app->urlManager->createUrl('admin/bid/index')]],

        ]],
    ],
]);
NavBar::end();