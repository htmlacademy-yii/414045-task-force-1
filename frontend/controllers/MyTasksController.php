<?php

namespace frontend\controllers;

class MyTasksController extends SecuredController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}