<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$bc[] = $name;
?>
<div class="site-error">

    <div class="row">
        <div class="col-xs-12">
            <?= \yii\widgets\Breadcrumbs::widget(['links' => $bc]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h1><?= Html::encode($this->title) ?></h1>

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                The above error occurred while the Web server was processing your request.
            </p>
            <p>
                Please contact us if you think this is a server error. Thank you.
            </p>
        </div>
    </div>


</div>