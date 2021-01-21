<?php
/**
 * Created by PhpStorm.
 * User: Shoy
 * Date: 17.11.2016
 * Time: 11:58
 */

namespace app\modules\admin;


class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->defaultRoute = 'good';
    }

}