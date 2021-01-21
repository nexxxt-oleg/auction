<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 21.07.2016
 * Time: 9:21
 */
/* @var $type string */
/* @var  $model \app\models\auction\GoodStringSearch */
$form = \yii\bootstrap\ActiveForm::begin([
    'options' => ['class' => $type == 'index' ? 'fixed-search__form' : 'search'],
    'action' => Yii::$app->urlManager->createUrl(['/good/search']),
    'fieldConfig' => [
        'options' => [
            'tag'=>'span'
        ]
    ]
]);
echo $form->field($model, 'searchString', [
    'template' => "\n{input}\n{error}"
])->input('text', ['class' => $type == 'index' ? 'fixed-search__input' : 'search__input', 'placeholder' => 'Введите название предмета']);
echo \yii\bootstrap\Html::submitButton(\yii\bootstrap\Html::img('/assets_b/img/icon/search2.svg'), ['class' => $type == 'index' ? 'fixed-search__button' : 'search__button']);
\yii\bootstrap\ActiveForm::end();