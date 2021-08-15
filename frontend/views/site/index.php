<?php

/* @var $this yii\web\View */

$this->title = 'TaskForce';

Yii::$app->getResponse()->redirect(\Components\Routes\Route::getTasks());

