<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets_b;

use lavrentiev\widgets\toastr\ToastrAsset;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/assets_b';
    public $css = [
        'css/jquery.fullPage.css',
        'css/animate.min.css',
        'css/magnific-popup.css',
        'css/ion.rangeSlider.css',
        'css/ion.rangeSlider.skinFlat.css',
        'css/MagnifierRentgen.css',
        'css/owl.carousel.css',
        'css/owl.theme.css',
        'css/main.css',
        'css/media.css',
    ];
    public $js = [
        'js/makeup/jquery.fullPage.min.js',
        'js/makeup/jquery.countdown.js',
        'js/makeup/jquery.inputmask.bundle.min.js',
        'js/makeup/wow.min.js',
        'js/makeup/jquery.magnific-popup.min.js',
        'js/makeup/ion.rangeSlider.min.js',
        'js/makeup/svgeezy.min.js',
        'js/makeup/MagnifierRentgen.js',
        'js/makeup/owl.carousel.min.js',
        'js/makeup/jquery.sticky-kit.min.js',
        'js/makeup/main.js',
        'js/functions.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        ToastrAsset::class,
    ];
}