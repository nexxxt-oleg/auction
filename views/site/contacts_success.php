<?php

use yii\helpers\Html;
use \app\models\ContactForm;

/* @var $this yii\web\View */
/* @var $exception Exception */
/* @var $model \app\models\ContactForm */

$this->title = 'Спасибо за обращение';
?>
<div class="row">
    <div class="col-xs-12">
        <ol class="breadcrumb clearfix">
            <li><a href="#">Главная</a></li>
            <li><a href="#">Часто задаваемые вопросы</a></li>
            <li class="active"><?= $this->title?></li>
        </ol>
    </div>

    <div class="col-xs-12">
        <h2 class="content-title"><?= $this->title?></h2>
    </div>
</div>

<div class="row">
    <?php
    switch ($model->type) {
        case ContactForm::TYPE_COMMON:
        default:
            $typeMsg = 'Благодарим за Ваше обращение.';
            break;
        case ContactForm::TYPE_COMMENT:
            $typeMsg = 'Благодарим за Ваше предложение';
            break;
        case ContactForm::TYPE_CALLBACK:
            $typeMsg = 'Благодарим за Вашу заявку. Мы перезвоним Вам в ближайшее время.';
            break;
        case ContactForm::TYPE_QUESTION:
            $typeMsg = 'Благодарим за Ваш вопрос';
            break;
    }
    ?>
    <p class="well well-lg text-success"><?= $typeMsg?></p>
</div>