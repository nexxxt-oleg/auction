<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 21.07.2016
 * Time: 9:56
 */

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $goodSearch \app\models\auction\GoodSearch */

$this->title = 'Аукционы';
\app\assets_b\GoodIndexAsset::register($this);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="row">
            <div class="auction-list clearfix">
                <?php
                echo \yii\widgets\ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_view',
                    'viewParams' => ['cart' => new \app\components\shop\MyShoppingCart()],
                    'itemOptions' => ['class' => 'col-xs-12 col-md-6 col-lg-4'],
                    'summary'=>'',
                ]);?>
            </div>
        </div>
    </div>
</div>