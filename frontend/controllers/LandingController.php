<?php

declare(strict_types=1);

namespace frontend\controllers;

use Components\Constants\TaskConstants;
use frontend\models\LoginForm;
use frontend\models\Task;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

final class LandingController extends Controller
{
    public $layout = 'landing';

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $loginForm = new LoginForm();
        $latestTasks = Task::find()->orderBy('id desc')->limit(TaskConstants::COUNT_SHOW_TASKS_IN_LANDING_PAGE)->all();
        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());
            if (Yii::$app->request->isAjax && $loginForm->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('index', compact('loginForm', 'latestTasks'));
    }
}