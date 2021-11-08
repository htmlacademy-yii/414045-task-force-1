<?php

namespace frontend\assets;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css
        = [
            'css/normalize.css',
            'css/style.css',
        ];
    public $js
        = [
            'js/main.js'
        ];
    public $depends
        = [
            YiiAsset::class,
        ];
}
