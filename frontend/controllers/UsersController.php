<?php


namespace frontend\controllers;


use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}