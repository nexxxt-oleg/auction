<?php

namespace app\models\auction;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FilterForm extends Model
{
    public $categoryId;
    public $filterId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['categoryId', 'filterId'], 'number'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'categoryId' => 'Идентификатор категории',
            'filterId' => 'Идентификатор фильтра'
        ];
    }

    /**
     * @param $form \yii\bootstrap\ActiveForm
     * @return string
     *
     */
    public function render($form) {
        $html = '<div class="auction-sort__slider">
            <p>цена, руб.:</p>
            <input class="price-slider" type="text" name="name" value="" />
            </div>';

        $arCategory = Category::find()->where(['active' => 'Y'])->all();
        /** @var $category Category */
        foreach ($arCategory as $category) {
            $html .= $form->field($this, 'categoryId', [
                'options' => ['class' => 'form__group form__group--title'],
                'template' => "\n{input}\n{beginLabel}\n$category->name\n{endLabel}\n{error}\n{hint}\n",
                'labelOptions' => ['class' => ''],
            ])->checkbox([], false);


            $arParentFilter = Filter::find()->where(['category_id' => $category->id, 'active' => 'Y', 'level' => 1])->all();
            /** @var $parentFilter Filter */
            foreach ($arParentFilter as $parentFilter) {
                $html .= '<ul class="auction-sort__list">';
                $html .= "<div class='auction-sort__list-title'>
                    $parentFilter->name
                    </div>";

                $arFilter = Filter::find()->where(['parent' => $parentFilter->id, 'active' => 'Y', 'level' => 2])->all();
                /** @var $filter Filter */
                foreach ($arFilter as $filter) {
                    $html .= $form->field($this, 'filterId', [
                        'options' => ['class' => ''],
                        'template' => "<li class=\"form__group\">\n{input}\n{beginLabel}\n$filter->name\n{endLabel}\n{error}\n{hint}\n</li>",
                        'labelOptions' => ['class' => ''],
                    ])->checkbox([], false);
                }
                $html .= '<div class="clearfix"></div></ul>';
            }
        }
        return $html;
    }
}