<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 19.07.2016
 * Time: 13:31
 */
/* @var $type string */
/* @var $arCatModel array */
/** @var  $auFlag string */
use \app\models\auction\Auction;
?>
<?php $clearUrl = "/".Yii::$app->request->getPathInfo()
    .((isset($_REQUEST['GoodSearch']['top_menu'])) ? "?".urlencode("GoodSearch[top_menu]")."=".$_REQUEST['GoodSearch']['top_menu'] : '')
    .((isset($_REQUEST['GoodSearch']['next_flag'])) ? "&".urlencode("GoodSearch[next_flag]")."=".$_REQUEST['GoodSearch']['next_flag'] : '');?>
<?= \yii\helpers\Html::tag('span', $clearUrl, ['id' => 'clearUrl', 'style' => ['display' => 'none']]);?>
<ul <?= ($type == 'index' ? 'class="navigation-desktop wow fadeInLeft" data-wow-duration="1s"' : 'class="navigation-desktop navigation-desktop--green"')?>>
    <li class="navigation-desktop__item">
        <?php $arNextParams = ['/good/index', 'GoodSearch[top_menu]' => "next", 'GoodSearch[next_flag]' => $auFlag];?>
        <a href="<?= Yii::$app->urlManager->createUrl($arNextParams)?>" class="navigation-desktop__link
        <?php
        if($clearUrl == Yii::$app->urlManager->createUrl($arNextParams)) {
            echo 'navigation-desktop__link--active';
        }
        ?>
        ">
        <?php
        switch($auFlag){
            case Auction::NEAREST_FLAG:
            default:
                echo 'Ближайший аукцион';
                break;
            case Auction::ACTIVE_FLAG:
                echo 'Текущий аукцион';
                break;
            case Auction::PAST_FLAG:
                echo 'Прошедший аукцион';
                break;
        }
        ?>
        </a>
    </li>
    <li class="navigation-desktop__item">
        <a href="<?= Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "all"])?>" class="navigation-desktop__link
        <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "all"])) ? 'navigation-desktop__link--active' : ''?>">Все лоты</a>
    </li>
    <?php /* @var $category \app\models\auction\Category */
    foreach($arCatModel as $category):?>
        <li class="navigation-desktop__item">
            <a href="<?= Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "category$category->id"])?>" class="navigation-desktop__link
            <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/good/index', 'GoodSearch[top_menu]' => "category$category->id"])) ? 'navigation-desktop__link--active' : ''?>">
                <?= $category->name?>
            </a>
        </li>

    <?php endforeach?>
    <li class="navigation-desktop__item">
        <a href="<?= Yii::$app->urlManager->createUrl(['/site/faq'])?>" class="navigation-desktop__link
        <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/site/faq'])) ? 'navigation-desktop__link--active' : ''?>">Узнайте подробнее</a>
    </li>
    <li class="navigation-desktop__item">
        <a href="<?= Yii::$app->urlManager->createUrl(['/site/contacts'])?>" class="navigation-desktop__link
        <?= ($clearUrl == Yii::$app->urlManager->createUrl(['/site/contacts'])) ? 'navigation-desktop__link--active' : ''?>">
            Контакты
        </a>
    </li>
</ul>
<script type="text/javascript">
    window.clearUrl = "<?= $clearUrl?>";
</script>