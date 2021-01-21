<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 13:31
 */
/* @var $type string */
/* @var $arCatModel \app\models\auction\Category[] */
/* @var $goodReflection \ReflectionClass */
/* @var $filterMakeupTemplate array */
/* @var $categoryTemplateClass array */
use \app\models\auction\Filter;
?>
<?php foreach ($arCatModel as $parentKey => $category):?>
<div <?= $type == "index" ? 'class="col-xs-12 col-sm-6"' : (isset($categoryTemplateClass[$parentKey]) ? $categoryTemplateClass[$parentKey] : 'class="col-xs-12 col-sm-2 col-md-2"')?>>
    <h<?= $type == "index" ? '2' : '6'?> class="<?= $type == "index" ? 'section__title2' : 'footer-content__title'?>"><?= $category->name?></h<?= $type == "index" ? '2' : '6'?>>
    <?php $arParentFilter = \app\models\auction\Filter::find()->where(['category_id' => $category->id, 'active' => 'Y', 'level' => 1])->orderBy(['id' => SORT_DESC])->all();?>
        <?php /** @var $arParentFilter \app\models\auction\Filter[] */?>
        <?php foreach($arParentFilter as $key => $parentFilter):?>
        <ul class="catalog__list catalog__list--<?= isset($filterMakeupTemplate[$key]) ? $filterMakeupTemplate[$key] : '50'?> <?= $type == 'main' ? 'catalog__list--footer' : ''?>">
            <h6 class="catalog__title">
                <?= $parentFilter->name?>
            </h6>
            <?php $arFilter = \app\models\auction\Filter::find()->where(['parent' => $parentFilter->id, 'active' => 'Y', 'level' => 2])->all();
            /** @var $arFilter \app\models\auction\Filter[] */
            foreach ($arFilter as $filter):
                $goodCnt = \app\models\auction\Good::find()
                    ->innerJoin('{{%good_filters}}', '{{%good_filters}}.good_id = {{%good}}.id')
                    ->where(['{{%good_filters}}.filter_id' => $filter->id])->count();
                if($goodCnt > 0):?>
                <li class="catalog__item catalog__item--<?= $parentFilter->column_view == Filter::COLUMN_VIEW_1 ? '100' : '50'?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/good/index', $goodReflection->getShortName()=>['filter_id'=>[$filter->id => $filter->id]]])?>"><?= $filter->name?></a>
                </li>
                <?php endif?>
                    <?php endforeach?>
        </ul>
        <?php endforeach?>
</div>
<?php endforeach?>