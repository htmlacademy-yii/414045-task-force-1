<?php

namespace frontend\controllers;

use Components\Categories\CategoryHelper;
use Components\Constants\TaskConstants;
use Components\Routes\Route;
use frontend\models\Task;
use frontend\models\TaskAttachment;
use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

class CreateController extends SecuredController
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
                $attachmentFileNames = Yii::$app->session->get('attachmentFileNames') ?? null;

                foreach ($attachmentFileNames as $fileName) {
                    $file = new TaskAttachment();
                    $file->task_id = $task->id;
                    $file->file_base_name = $fileName['baseName'];
                    $file->file_name = $fileName['name'];
                    $file->file_src = TaskAttachment::UPLOAD_DIR . $fileName['name'];
                    if ($file->validate()) {
                        $file->save();
                    }
                }

                Yii::$app->session->remove('attachmentFileNames');
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
                $file->saveAs(TaskAttachment::UPLOAD_DIR . $name);
            }
        }
    }
}