<?php

namespace app\models;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * ContactForm is the model behind the contact form.
 */
class CallBackForm extends ContactForm
{
    public $type = self::TYPE_CALLBACK;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            ['type', 'compare', 'compareValue' => self::TYPE_CALLBACK, 'operator' => '=='],
        ];
    }

    public function renderIndex() {
        ActiveForm::begin([
            'options' => ['class' => 'section__form clearfix'],
            'action' => Yii::$app->urlManager->createUrl('site/call_back'),
        ]);
        echo Html::activeInput('hidden', $this, 'type');
        echo Html::activeInput('text', $this, 'phone', ['class' => 'section__form-input-phone', 'placeholder' => '+7 (___) ___ __ __']);
        echo Html::submitButton('заказать звонок', ['class' => 'section__form-button']);

        ActiveForm::end();
    }

    public function renderMain() {
        $form = ActiveForm::begin([
            'options' => ['class' => 'section__form clearfix'],
            'action' => Yii::$app->urlManager->createUrl('site/call_back'),
            'fieldConfig' => [
                'inputOptions' => ['class' => 'form__input'],
                'labelOptions' => ['class' => 'form__label']
            ]
        ]);
        echo Html::activeInput('hidden', $this, 'type');
        echo $form->field($this, 'name');
        echo $form->field($this, 'phone');
        echo Html::submitButton('заказать звонок', ['class' => 'default-button default-button--call']);

        ActiveForm::end();
    }
}