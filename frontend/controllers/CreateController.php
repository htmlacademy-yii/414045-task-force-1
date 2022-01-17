<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Categories\CategoryService;
use Components\Constants\TaskConstants;
use Components\Constants\UserConstants;
use Components\Locations\LocationService;
use Components\Routes\Route;
use Components\Tasks\TaskService;
use frontend\models\Task;
use frontend\models\TaskAttachment;
use frontend\models\User;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;

final class CreateController extends SecuredController
{
    public Task $task;

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $user = User::findOne(Yii::$app->user->id);

        if ($user->role !== UserConstants::USER_ROLE_CUSTOMER) {
            $this->redirect(Route::getTasks());
        }

        $task = $this->task ?? new Task();
        $categories = (new CategoryService())->getCategoryNamesForDB();

        return $this->render('index', compact('task', 'categories'));
    }

    /**
     * @return Response
     */
    public function actionCheckForm(): Response
    {
        $user = User::findOne(Yii::$app->user->id);
        $task = new Task();

        if (Yii::$app->request->isPost) {
            $task->load(Yii::$app->request->post());
            $task->customer_id = $user->id;
            $task->state = TaskConstants::NEW_TASK_STATUS_NAME;
            $task->location_point = $task->address !== null ? (new LocationService($task->address))->getLocationPoint() : '';

            if ($task->validate()) {
                $task->save();
                $attachmentFileNames = Yii::$app->session->get('attachmentFileNames') ?? null;

                (new TaskService())->saveTaskAttachmentFiles($attachmentFileNames, $task->id);
                Yii::$app->session->remove('attachmentFileNames');

                return $this->redirect(Route::getTasks());
            }
            $this->task = $task;
        }

        return $this->redirect(Route::getTaskCreate());
    }

    /**
     * @return void
     */
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