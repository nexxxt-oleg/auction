<?php

/* @var $this yii\web\View */
/* @var $exception Exception */
/** @var  $arMsg array */

$this->title = $arMsg['title'];
?>
<p class="well well-lg <?=$arMsg['css_class']?>"><?=$arMsg['msg']?></p>