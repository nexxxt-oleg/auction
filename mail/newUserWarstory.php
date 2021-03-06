<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 13.07.2016
 * Time: 12:02
 */
/* @var $user \app\models\auth\User */
/* @var $password string*/
?>
<p>
<?= Yii::$app->name?>, На сайте аукциона военного антиквариата по адресу <?= Yii::$app->urlManager->createAbsoluteUrl('/')?> появилась регистрационная запись,
в которой был указал ваш электронный адрес (e-mail).
</p>
<p>Имя пользователя:<?= $user->login?></p>
<p>Пароль:<?= $password?></p>
<p>Если вы не понимаете, о чем идет речь — просто проигнорируйте это сообщение!</p>
<p>Если же именно вы решили зарегистрироваться на сайте по адресу <?= Yii::$app->urlManager->createAbsoluteUrl('/')?>,
то вам следует подтвердить свою регистрацию и тем самым активировать вашу учетную запись.
Подтверждение регистрации производится один раз и необходимо для повышения безопасности сайта и защиты его от злоумышленников.
Чтобы активировать вашу учетную запись, необходимо перейти по ссылке:</p>
<p><a href="<?= Yii::$app->urlManager->createAbsoluteUrl('/site/confirm_registration?auth_key='.$user->auth_key)?>">Активировать учетную запись</a></p>
<p>Активация произойдет автоматически.</p>
<p>После активации учетной записи вы сможете войти в личный кабинет, используя email и пароль.</p>
<p>Благодарим за регистрацию!</p>