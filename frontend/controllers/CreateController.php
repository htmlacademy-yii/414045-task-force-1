<?php

namespace frontend\controllers;

use Components\Categories\CategoryHelper;
use Components\Routes\Route;
use frontend\models\Task;
use frontend\models\UploadTaskAttachmentsFiles;
use yii\web\Controller;

class CreateController extends Controller
{
    public function actionIndex(): string
    {
        $task = new Task();
        $files = new UploadTaskAttachmentsFiles();
        $categories = CategoryHelper::getCategoryNames();

        if (\Yii::$app->request->getIsPost()){
            $task->load(\Yii::$app->request->post());
            if ($task->validate()){
                $task->save();
                $this->redirect(Route::getTasks());
            }
        }

        return $this->render('index', compact('task', 'files', 'categories'));
    }
}