<?php

namespace frontend\assets;

use yii\web\AssetBundle;

final class CreateTaskAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/dropzone.js',
        'js/createDropzone.js',
    ];
}