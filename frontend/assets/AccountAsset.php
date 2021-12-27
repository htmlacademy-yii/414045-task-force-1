<?php

namespace frontend\assets;

class AccountAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/dropzone.js',
        'js/accountDropzone.js'
    ];
}