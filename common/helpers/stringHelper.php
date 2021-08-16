<?php

use yii\helpers\Html;

if (!function_exists('encode')) {
    function encode($string): string
    {
        return Html::encode($string);
    }
}
