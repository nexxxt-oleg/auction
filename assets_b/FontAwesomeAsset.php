<?php
/**
 * Created by PhpStorm.
 * User: afilatov
 * Date: 18.07.2016
 * Time: 16:32
 */

namespace app\assets_b;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle{
    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $css = [ 'css/font-awesome.css',];
}