<?php

namespace frontend\controllers;

use Components\Categories\CategoryHelper;
use Components\Constants\TaskConstants;
use Components\Routes\Route;
use frontend\models\Task;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class CreateController extends Controller
{
    public Task $task;

    public function actionIndex()
    {
        $user = Yii::$app->user;
        $task = new Task();
        $categories = CategoryHelper::getCategoryNamesForDB();

        if (Yii::$app->request->isPost) {
            $task->load(Yii::$app->request->post());
            $task->customer_id = $user->id;
            $task->state = TaskConstants::NEW_TASK_STATUS_NAME;
            if ($task->validate()) {
                $task->save();
                $this->redirect(Route::getTasks());
            }
        }

        return $this->render('index', compact('task', 'categories'));
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {
            $attachmentFiles = UploadedFile::getInstancesByName('attachmentFiles');
            $attachmentFileNames = Yii::$app->session->get('attachmentFileNames') ?? [];
            foreach ($attachmentFiles as $file) {
                $name = uniqid('upload_') . '.' . $file->extension;
                $attachmentFileNames[] = ['name' => $name, 'baseName' => $file->baseName];
                Yii::$app->session->set('attachmentFileNames', $attachmentFileNames);
                $file->saveAs('@webroot/uploads/' . $name);
            }
        }
    }
}